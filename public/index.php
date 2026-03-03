<?php

declare(strict_types=1);

use Lubix\Core\Exceptions\Handler;
use Lubix\Core\Http\Request;

$app = require __DIR__ . '/../bootstrap/app.php';

try {
    $request = Request::fromGlobals();
    $response = $app->handle($request);
    $response->send();
} catch (Throwable $e) {
    $handler = new Handler();
    $request = Request::fromGlobals();
    $response = $handler->render($request, $e);
    $response->send();
}
