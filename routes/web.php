<?php

declare(strict_types=1);

use Lubix\Core\Support\Vite;
use Lubix\Core\Http\Request;
use Lubix\Core\Http\Response;

return function ($router): void {
    $router->get('/{path}', function (Request $request): Response {
        $viteTags = Vite::tags('index.html');
        
        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>LubiX</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Plus Jakarta Sans', sans-serif; }
            </style>
            {$viteTags}
        </head>
        <body class="bg-white">
            <div id="root"></div>
        </body>
        </html>
        HTML;

        return Response::html($html);
    });

    $router->get('/', function (Request $request): Response {
        $viteTags = Vite::tags('index.html');
        
        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>LubiX</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Plus Jakarta Sans', sans-serif; }
            </style>
            {$viteTags}
        </head>
        <body class="bg-white">
            <div id="root"></div>
        </body>
        </html>
        HTML;

        return Response::html($html);
    });
};
