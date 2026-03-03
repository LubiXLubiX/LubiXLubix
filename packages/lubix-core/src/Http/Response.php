<?php

declare(strict_types=1);

namespace Lubix\Core\Http;

final class Response
{
    /** @var array<string, string> */
    private array $headers = [];

    public function __construct(
        private string $body = '',
        private int $status = 200
    ) {
    }

    public static function text(string $body, int $status = 200): self
    {
        $r = new self($body, $status);
        $r->header('Content-Type', 'text/plain; charset=utf-8');
        return $r;
    }

    public static function html(string $body, int $status = 200): self
    {
        $r = new self($body, $status);
        $r->header('Content-Type', 'text/html; charset=utf-8');
        return $r;
    }

    public static function json(mixed $data, int $status = 200): self
    {
        $body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!is_string($body)) {
            $body = '{"error":"json_encode_failed"}';
            $status = 500;
        }

        $r = new self($body, $status);
        $r->header('Content-Type', 'application/json; charset=utf-8');
        return $r;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function status(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->body;
    }
}
