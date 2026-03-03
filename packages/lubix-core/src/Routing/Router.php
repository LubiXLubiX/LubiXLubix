<?php

declare(strict_types=1);

namespace Lubix\Core\Routing;

use Lubix\Core\Http\Request;

final class Router
{
    /** @var array<int, Route> */
    private array $routes = [];

    public function get(string $pathPattern, mixed $handler): Route
    {
        return $this->map(['GET'], $pathPattern, $handler);
    }

    public function post(string $pathPattern, mixed $handler): Route
    {
        return $this->map(['POST'], $pathPattern, $handler);
    }

    public function put(string $pathPattern, mixed $handler): Route
    {
        return $this->map(['PUT'], $pathPattern, $handler);
    }

    public function patch(string $pathPattern, mixed $handler): Route
    {
        return $this->map(['PATCH'], $pathPattern, $handler);
    }

    public function delete(string $pathPattern, mixed $handler): Route
    {
        return $this->map(['DELETE'], $pathPattern, $handler);
    }

    /** @param array<int, string> $methods */
    public function map(array $methods, string $pathPattern, mixed $handler): Route
    {
        $route = new Route($methods, $pathPattern, $handler);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * @return array{0: mixed, 1: array<string, string>} handler and params
     */
    public function match(Request $request): array
    {
        $method = strtoupper($request->method);
        $path = $request->path;

        foreach ($this->routes as $route) {
            if (!in_array($method, $route->methods, true)) {
                continue;
            }

            $params = [];
            if ($this->matchPattern($route->pathPattern, $path, $params)) {
                return [$route->handler, $params];
            }
        }

        return [null, []];
    }

    /**
     * @param array<string, string> $params
     */
    private function matchPattern(string $pattern, string $path, array &$params): bool
    {
        $params = [];

        $pattern = rtrim($pattern, '/') ?: '/';
        $path = rtrim($path, '/') ?: '/';

        $patternParts = explode('/', trim($pattern, '/'));
        $pathParts = explode('/', trim($path, '/'));

        if ($pattern === '/' && $path === '/') {
            return true;
        }

        if (count($patternParts) !== count($pathParts)) {
            return false;
        }

        foreach ($patternParts as $i => $part) {
            $candidate = $pathParts[$i] ?? '';

            if ($part !== '' && $part[0] === '{' && str_ends_with($part, '}')) {
                $name = trim($part, '{}');
                if ($name === '') {
                    return false;
                }
                $params[$name] = $candidate;
                continue;
            }

            if ($part !== $candidate) {
                return false;
            }
        }

        return true;
    }
}
