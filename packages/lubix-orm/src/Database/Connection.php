<?php

declare(strict_types=1);

namespace Lubix\Orm\Database;

use PDO;
use PDOException;
use RuntimeException;

final class Connection
{
    private static ?PDO $pdo = null;

    /**
     * @param array{dsn:string, user?:string, password?:string, options?:array<int|string, mixed>} $config
     */
    public static function configure(array $config): void
    {
        $dsn = $config['dsn'] ?? '';
        if ($dsn === '') {
            throw new RuntimeException('DB DSN is required');
        }

        $user = $config['user'] ?? null;
        $password = $config['password'] ?? null;
        $options = $config['options'] ?? [];

        $defaults = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            self::$pdo = new PDO($dsn, $user, $password, $options + $defaults);
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to connect to database: ' . $e->getMessage(), 0, $e);
        }
    }

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            throw new RuntimeException('Database connection is not configured');
        }
        return self::$pdo;
    }

    public static function isConfigured(): bool
    {
        return self::$pdo !== null;
    }
}
