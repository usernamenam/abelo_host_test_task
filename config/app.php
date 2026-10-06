<?php

declare(strict_types=1);

use App\Framework\Env;

return [
    'db' => [
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => (int) Env::get('DB_PORT', '3306'),
        'database' => Env::get('DB_DATABASE'),
        'username' => Env::get('DB_USERNAME'),
        'password' => Env::get('DB_PASSWORD'),
        'charset' => 'utf8mb4',
    ],
    'paths' => [
        'templates' => dirname(__DIR__) . '/templates',
        'cache' => dirname(__DIR__) . '/var/smarty/cache',
        'compile' => dirname(__DIR__) . '/var/smarty/compile',
    ],
];
