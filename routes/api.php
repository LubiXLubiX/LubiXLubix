<?php

declare(strict_types=1);

use App\Http\Controllers\NoteController;
use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;
use Lubix\Core\Support\Env;

return function ($router): void {
    $router->get('/api/health', function (Request $request): Response {
        return Response::json([
            'ok' => true,
            'framework' => 'LubiX',
            'env' => Env::get('APP_ENV', 'prod'),
        ]);
    });

    // Notes API using Controller
    $noteController = new NoteController();

    $router->get('/api/notes', [$noteController, 'index']);
    $router->get('/api/notes/{id}', [$noteController, 'show']);
    $router->post('/api/notes', [$noteController, 'store']);
};
