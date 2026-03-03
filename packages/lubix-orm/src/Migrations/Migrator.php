<?php

declare(strict_types=1);

namespace Lubix\Orm\Migrations;

use Lubix\Orm\Database\Connection;
use PDO;
use RuntimeException;

final class Migrator
{
    private string $migrationsPath;

    public function __construct(string $migrationsPath)
    {
        $this->migrationsPath = rtrim($migrationsPath, '/');
    }

    public function migrate(): void
    {
        $pdo = Connection::pdo();
        $this->ensureMigrationsTable($pdo);

        $applied = $this->appliedMigrations($pdo);
        $files = glob($this->migrationsPath . '/*.php') ?: [];
        sort($files);

        foreach ($files as $file) {
            $name = basename($file);
            if (isset($applied[$name])) {
                continue;
            }

            $migration = require $file;
            if (!is_object($migration) || !method_exists($migration, 'up')) {
                throw new RuntimeException('Invalid migration file: ' . $name);
            }

            $pdo->beginTransaction();
            try {
                $migration->up($pdo);
                $stmt = $pdo->prepare('INSERT INTO `lubix_migrations` (`name`, `applied_at`, `batch`) VALUES (:name, NOW(), :batch)');
                $stmt->execute([
                    'name' => $name,
                    'batch' => $this->getNextBatchNumber($pdo)
                ]);
                $pdo->commit();
            } catch (\Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                throw $e;
            }
        }
    }

    public function rollback(int $steps = 1): void
    {
        $pdo = Connection::pdo();
        $this->ensureMigrationsTable($pdo);

        for ($i = 0; $i < $steps; $i++) {
            $lastBatch = $this->getLastBatchNumber($pdo);
            if ($lastBatch === 0) {
                break;
            }

            $stmt = $pdo->prepare('SELECT `name` FROM `lubix_migrations` WHERE `batch` = :batch ORDER BY `id` DESC');
            $stmt->execute(['batch' => $lastBatch]);
            $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($migrations)) {
                break;
            }

            foreach ($migrations as $name) {
                $file = $this->migrationsPath . '/' . $name;
                if (!is_file($file)) {
                    throw new RuntimeException("Migration file not found for rollback: {$name}");
                }

                $migration = require $file;
                if (!is_object($migration) || !method_exists($migration, 'down')) {
                    throw new RuntimeException("Migration {$name} does not support rollback (no down method)");
                }

                $pdo->beginTransaction();
                try {
                    $migration->down($pdo);
                    $stmt = $pdo->prepare('DELETE FROM `lubix_migrations` WHERE `name` = :name');
                    $stmt->execute(['name' => $name]);
                    $pdo->commit();
                    echo "Rolled back: {$name}\n";
                } catch (\Throwable $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    throw $e;
                }
            }
        }
    }

    private function getNextBatchNumber(PDO $pdo): int
    {
        return $this->getLastBatchNumber($pdo) + 1;
    }

    private function getLastBatchNumber(PDO $pdo): int
    {
        $stmt = $pdo->query('SELECT MAX(`batch`) FROM `lubix_migrations`');
        return (int) $stmt->fetchColumn() ?: 0;
    }

    private function ensureMigrationsTable(PDO $pdo): void
    {
        $pdo->exec('CREATE TABLE IF NOT EXISTS `lubix_migrations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY, 
            `name` VARCHAR(255) NOT NULL UNIQUE, 
            `batch` INT NOT NULL DEFAULT 1,
            `applied_at` DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        
        // Ensure batch column exists for older tables
        try {
            $pdo->exec('ALTER TABLE `lubix_migrations` ADD COLUMN `batch` INT NOT NULL DEFAULT 1 AFTER `name`');
        } catch (\PDOException $e) {
            // Column already exists, ignore
        }
    }

    /** @return array<string, bool> */
    private function appliedMigrations(PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT `name` FROM `lubix_migrations`');
        $rows = $stmt->fetchAll();
        $out = [];
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if (is_array($row) && isset($row['name'])) {
                    $out[(string) $row['name']] = true;
                }
            }
        }
        return $out;
    }
}
