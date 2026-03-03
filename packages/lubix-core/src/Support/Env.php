<?php

declare(strict_types=1);

namespace Lubix\Core\Support;

final class Env
{
    /** @var array<string, string> */
    private static array $loaded = [];

    public static function load(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if (!is_array($lines)) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            if ($key === '') {
                continue;
            }

            $value = self::stripQuotes($value);

            self::$loaded[$key] = $value;
            if (getenv($key) === false) {
                putenv($key . '=' . $value);
            }
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
            }
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $v = getenv($key);
        if ($v !== false) {
            return (string) $v;
        }
        if (isset(self::$loaded[$key])) {
            return self::$loaded[$key];
        }
        return $default;
    }

    private static function stripQuotes(string $value): string
    {
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            return substr($value, 1, -1);
        }
        return $value;
    }
}
