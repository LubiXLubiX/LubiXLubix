<?php

declare(strict_types=1);

namespace Lubix\Cli\Console\Commands;

use Lubix\Cli\Console\CommandInterface;
use Lubix\Core\Support\Env;
use Lubix\Orm\Database\Connection;
use Lubix\Orm\Migrations\Migrator;

final class RollbackCommand implements CommandInterface
{
    public function name(): string
    {
        return 'migrate:rollback';
    }

    public function description(): string
    {
        return 'Rollback database migrations';
    }

    public function run(array $args): int
    {
        $cwd = getcwd() ?: '.';
        $target = $args[0] ?? '.';
        $steps = isset($args[1]) ? (int)$args[1] : 1;
        
        $appPath = rtrim($cwd, '/') . '/' . trim($target, '/');
        $envPath = $appPath . '/.env';
        
        if (is_file($envPath)) {
            Env::load($envPath);
        }

        $host = Env::get('DB_HOST', '127.0.0.1');
        $port = Env::get('DB_PORT', '3306');
        $db = Env::get('DB_DATABASE');
        $user = Env::get('DB_USER', 'root');
        $pass = Env::get('DB_PASSWORD', '');

        if (!$db) {
            fwrite(STDERR, "DB_DATABASE not set in .env\n");
            return 1;
        }

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        
        try {
            Connection::configure([
                'dsn' => $dsn,
                'user' => $user,
                'password' => $pass,
            ]);
        } catch (\Throwable $e) {
            fwrite(STDERR, "Connection failed: " . $e->getMessage() . "\n");
            return 1;
        }

        $migrationsPath = $appPath . '/database/migrations';
        if (!is_dir($migrationsPath)) {
            fwrite(STDERR, "Migrations directory not found: {$migrationsPath}\n");
            return 1;
        }

        $migrator = new Migrator($migrationsPath);
        $migrator->rollback($steps);

        return 0;
    }
}
