<?php

use App\Framework\DatabaseConnection;
use App\Framework\Env;

require __DIR__ . '/../vendor/autoload.php';

$schema = file_get_contents(__DIR__ . '/../database/schema.sql');
if ($schema === false) {
    throw new RuntimeException('Не удалось прочитать файл схемы.');
}

Env::load(__DIR__ . '/../.env');
$config = require __DIR__ . '/../config/app.php';
$pdo = DatabaseConnection::make($config['db']);

$pdo->exec($schema);

echo "БД инициализирована";