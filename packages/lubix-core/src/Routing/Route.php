<?php

declare(strict_types=1);

namespace Lubix\Core\Routing;

final class Route
{
    public array $methods;
    public string $pathPattern;
    public mixed $handler;
    public ?string $name = null;
    public array $middleware = [];

    /** @param array<int, string> $methods */
    public function __construct(array $methods, string $pathPattern, mixed $handler)
    {
        $this->methods = $methods;
        $this->pathPattern = $pathPattern;
        $this->handler = $handler;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function middleware(string|array $middleware): self
    {
        if (is_string($middleware)) {
            $this->middleware[] = $middleware;
        } else {
            $this->middleware = array_merge($this->middleware, $middleware);
        }
        return $this;
    }
}
