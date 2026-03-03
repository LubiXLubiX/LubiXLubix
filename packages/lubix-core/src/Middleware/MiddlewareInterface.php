<?php

declare(strict_types=1);

namespace Lubix\Core\Middleware;

use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;

interface MiddlewareInterface
{
    /** @param callable(Request): Response $next */
    public function handle(Request $request, callable $next): Response;
}
