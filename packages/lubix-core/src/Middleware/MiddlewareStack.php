<?php

declare(strict_types=1);

namespace Lubix\Core\Middleware;

use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;

final class MiddlewareStack
{
    /** @var array<int, MiddlewareInterface> */
    private array $middlewares = [];

    public function add(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /** @param callable(Request): Response $finalHandler */
    public function wrap(callable $finalHandler): callable
    {
        $next = $finalHandler;

        for ($i = count($this->middlewares) - 1; $i >= 0; $i--) {
            $middleware = $this->middlewares[$i];
            $next = function (Request $request) use ($middleware, $next): Response {
                return $middleware->handle($request, $next);
            };
        }

        return $next;
    }
}
