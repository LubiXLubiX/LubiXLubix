<?php

declare(strict_types=1);

namespace Lubix\Core;

use Lubix\Core\Exceptions\NotFoundException;
use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;
use Lubix\Core\Middleware\MiddlewareStack;
use Lubix\Core\Routing\Router;

final class Application
{
    private Router $router;
    private MiddlewareStack $middleware;

    public function __construct(?Router $router = null, ?MiddlewareStack $middleware = null)
    {
        $this->router = $router ?? new Router();
        $this->middleware = $middleware ?? new MiddlewareStack();
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function middleware(): MiddlewareStack
    {
        return $this->middleware;
    }

    public function handle(Request $request): Response
    {
        [$handler, $params] = $this->router->match($request);
        if ($handler === null) {
            throw new NotFoundException('Route not found: ' . $request->method . ' ' . $request->path);
        }

        $finalHandler = function (Request $request) use ($handler, $params): Response {
            if ($handler instanceof \Closure) {
                return $handler($request, $params);
            }
            
            if (is_array($handler) && count($handler) === 2) {
                [$instance, $method] = $handler;
                return $instance->$method($request, $params);
            }

            if (is_callable($handler)) {
                return $handler($request, $params);
            }

            return Response::text('Invalid handler', 500);
        };

        $pipeline = $this->middleware->wrap($finalHandler);
        return $pipeline($request);
    }
}
