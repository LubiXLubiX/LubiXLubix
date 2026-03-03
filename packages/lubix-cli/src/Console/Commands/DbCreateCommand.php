<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;
use Lubix\Core\Support\Env;
use PDO;
use RuntimeException;

final class DbCreateCommand implements CommandInterface
{
    public function name(): string
    {
        return 'db:create';
    }

    public function description(): string
    {
        return 'Create the configured MySQL database if it does not exist (default: root app)';
    }

    public function run(array $args): int
    {
        $cwd = getcwd() ?: '.';
        $repoRoot = $this->detectRepoRoot($cwd);
        $base = $repoRoot ?? $cwd;

        $target = $args[0] ?? '.';
        $appPath = rtrim($base, '/') . '/' . trim($target, '/');

        $envPath = $appPath . '/.env';
        Env::load($envPath);

        $host = Env::get('DB_HOST', '127.0.0.1') ?? '127.0.0.1';
        $port = Env::get('DB_PORT', '3306') ?? '3306';
        $db = Env::get('DB_DATABASE');
        if ($db === null || $db === '') {
            fwrite(STDERR, "DB_DATABASE is required in {$envPath}\n");
            return 1;
        }

        $user = Env::get('DB_USER', 'root') ?? 'root';
        $pass = Env::get('DB_PASSWORD', '') ?? '';
        $charset = Env::get('DB_CHARSET', 'utf8mb4') ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};charset={$charset}";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (\Throwable $e) {
            throw new RuntimeException('Failed to connect to MySQL server: ' . $e->getMessage(), 0, $e);
        }

        $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '``', $db) . '` CHARACTER SET ' . $charset . ' COLLATE ' . $charset . '_unicode_ci');

        echo "Database ensured: {$db}\n";
        return 0;
    }

    private function detectRepoRoot(string $startDir): ?string
    {
        $dir = $startDir;
        for ($i = 0; $i < 15; $i++) {
            if (is_dir($dir . '/packages')) {
                return $dir;
            }
            $parent = dirname($dir);
            if ($parent === $dir) {
                break;
            }
            $dir = $parent;
        }
        return null;
    }
}
