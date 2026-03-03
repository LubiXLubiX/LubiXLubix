<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;
use Lubix\Core\Support\Env;
use Lubix\Orm\Database\Connection;
use Lubix\Orm\Migrations\Migrator;

final class MigrateCommand implements CommandInterface
{
    public function name(): string
    {
        return 'migrate';
    }

    public function description(): string
    {
        return 'Run database migrations for an app (default: apps/demo-backend)';
    }

    public function run(array $args): int
    {
        $cwd = getcwd() ?: '.';
        $repoRoot = $this->detectRepoRoot($cwd);
        $base = $repoRoot ?? $cwd;

        // Default: monorepo root (or current directory if not a monorepo)
        $target = $args[0] ?? '.';
        $appPath = rtrim($base, '/') . '/' . trim($target, '/');

        $envPath = $appPath . '/.env';
        Env::load($envPath);

        $dsn = Env::get('DB_DSN');
        if ($dsn === null) {
            // Build default MySQL DSN from separate vars
            $host = Env::get('DB_HOST', '127.0.0.1');
            $port = Env::get('DB_PORT', '3306');
            $db = Env::get('DB_DATABASE');
            if ($db !== null) {
                $charset = Env::get('DB_CHARSET', 'utf8mb4');
                $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
            }
        }

        if ($dsn === null) {
            fwrite(STDERR, "DB config not found. Set DB_DSN or DB_HOST/DB_PORT/DB_DATABASE in {$envPath}\n");
            return 1;
        }

        $user = Env::get('DB_USER', 'root') ?? 'root';
        $pass = Env::get('DB_PASSWORD', '') ?? '';

        try {
            Connection::configure([
                'dsn' => $dsn,
                'user' => $user,
                'password' => $pass,
            ]);
        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'Unknown database')) {
                fwrite(STDERR, "Unknown database. Create it first, then rerun migrate.\n");
                fwrite(STDERR, "- Option A: php lubix db:create\n");
                fwrite(STDERR, "- Option B: create database manually in MySQL, using DB_DATABASE from .env\n\n");
            }
            throw $e;
        }

        $migrationsPath = $appPath . '/database/migrations';
        if (!is_dir($migrationsPath)) {
            fwrite(STDERR, "Migrations directory not found: {$migrationsPath}\n");
            return 1;
        }

        $migrator = new Migrator($migrationsPath);
        $migrator->migrate();

        echo "Migrations complete.\n";
        return 0;
    }

    private function detectRepoRoot(string $startDir): ?string
    {
        $dir = $startDir;
        for ($i = 0; $i < 15; $i++) {
            if (is_dir($dir . '/apps') && is_dir($dir . '/packages')) {
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
