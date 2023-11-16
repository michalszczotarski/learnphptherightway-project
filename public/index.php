<?php

declare(strict_types = 1);

use App\App;
use App\Config;
use App\Controllers\HomeController;
use App\Controllers\TransactionFileController;
use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');
define('ROOT', __DIR__ . '/../');
function show(mixed $data)
{
    echo "<pre>";
    echo var_dump($data);
    echo "</pre>";
}

$router = new Router();

$router
    ->get('/', [HomeController::class, 'index'])
    ->get('/upload', [TransactionFileController::class, 'index'])
    ->post('/upload', [TransactionFileController::class, 'upload'])
    ->get('/transactions', [TransactionFileController::class, 'transactions']);


(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
))->run();
