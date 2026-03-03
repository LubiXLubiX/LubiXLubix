<?php

declare(strict_types=1);

namespace Lubix\Orm\Model;

use Lubix\Orm\Database\Connection;
use PDO;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    /** @var array<string, mixed> */
    protected array $attributes = [];

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->attributes;
    }

    protected static function pdo(): PDO
    {
        return Connection::pdo();
    }

    protected static function table(): string
    {
        return static::$table;
    }

    public static function find(int|string $id): ?static
    {
        $table = static::table();
        $pk = static::$primaryKey;

        $stmt = static::pdo()->prepare("SELECT * FROM `{$table}` WHERE `{$pk}` = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!is_array($row)) {
            return null;
        }
        return new static($row);
    }

    /** @return array<int, static> */
    public static function all(int $limit = 100): array
    {
        $table = static::table();
        $limit = max(1, min($limit, 1000));

        $stmt = static::pdo()->query("SELECT * FROM `{$table}` LIMIT {$limit}");
        $rows = $stmt->fetchAll();
        if (!is_array($rows)) {
            return [];
        }

        $out = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                $out[] = new static($row);
            }
        }
        return $out;
    }

    public function save(): void
    {
        $table = static::table();
        $pk = static::$primaryKey;

        $attrs = $this->attributes;
        $id = $attrs[$pk] ?? null;

        if ($id === null) {
            unset($attrs[$pk]);
            $columns = array_keys($attrs);
            $placeholders = array_map(fn ($c) => ':' . $c, $columns);

            $sql = sprintf(
                'INSERT INTO `%s` (%s) VALUES (%s)',
                $table,
                implode(', ', array_map(fn ($c) => '`' . $c . '`', $columns)),
                implode(', ', $placeholders)
            );

            $stmt = static::pdo()->prepare($sql);
            $stmt->execute($attrs);
            $this->attributes[$pk] = (int) static::pdo()->lastInsertId();
            return;
        }

        $columns = array_filter(array_keys($attrs), fn ($c) => $c !== $pk);
        $sets = array_map(fn ($c) => '`' . $c . '` = :' . $c, $columns);

        $sql = sprintf('UPDATE `%s` SET %s WHERE `%s` = :__pk', $table, implode(', ', $sets), $pk);
        $params = $attrs;
        $params['__pk'] = $id;

        $stmt = static::pdo()->prepare($sql);
        $stmt->execute($params);
    }

    public function delete(): void
    {
        $table = static::table();
        $pk = static::$primaryKey;
        $id = $this->attributes[$pk] ?? null;
        if ($id === null) {
            return;
        }

        $stmt = static::pdo()->prepare("DELETE FROM `{$table}` WHERE `{$pk}` = :id");
        $stmt->execute(['id' => $id]);
    }
}
