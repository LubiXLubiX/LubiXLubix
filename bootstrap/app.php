<?php

declare(strict_types=1);

use Lubix\Core\Application;
use Lubix\Core\Support\Env;
use Lubix\Orm\Database\Connection;

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

$host = Env::get('DB_HOST', '127.0.0.1');
$port = Env::get('DB_PORT', '3306');
$db = Env::get('DB_DATABASE');
$charset = Env::get('DB_CHARSET', 'utf8mb4');
$user = Env::get('DB_USER', 'root');
$pass = Env::get('DB_PASSWORD', '');

if ($db !== null) {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
    Connection::configure([
        'dsn' => $dsn,
        'user' => $user ?? 'root',
        'password' => $pass ?? '',
    ]);
}

$app = new Application();

$web = require __DIR__ . '/../routes/web.php';
$api = require __DIR__ . '/../routes/api.php';

$web($app->router());
$api($app->router());

return $app;
