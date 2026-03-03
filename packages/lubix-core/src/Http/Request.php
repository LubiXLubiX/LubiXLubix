<?php

declare(strict_types=1);

namespace Lubix\Core\Http;

final class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $query,
        public readonly array $headers,
        public readonly string $rawBody,
        public readonly array $cookies,
        public readonly array $server,
        public readonly array $files,
        public readonly array $post
    ) {
    }

    public static function fromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        $headers = function_exists('getallheaders') ? (getallheaders() ?: []) : [];
        if (!is_array($headers)) {
            $headers = [];
        }

        $rawBody = file_get_contents('php://input');
        if (!is_string($rawBody)) {
            $rawBody = '';
        }

        return new self(
            method: strtoupper((string) $method),
            path: $path,
            query: $_GET,
            headers: $headers,
            rawBody: $rawBody,
            cookies: $_COOKIE,
            server: $_SERVER,
            files: $_FILES,
            post: $_POST
        );
    }

    public function header(string $name, ?string $default = null): ?string
    {
        foreach ($this->headers as $k => $v) {
            if (strcasecmp((string) $k, $name) === 0) {
                return is_string($v) ? $v : (is_array($v) ? (string) ($v[0] ?? $default) : $default);
            }
        }
        return $default;
    }

    public function json(bool $assoc = true): mixed
    {
        $contentType = (string) ($this->header('Content-Type') ?? '');
        if (stripos($contentType, 'application/json') === false) {
            return null;
        }
        if ($this->rawBody === '') {
            return null;
        }

        $decoded = json_decode($this->rawBody, $assoc);
        return $decoded;
    }
}
