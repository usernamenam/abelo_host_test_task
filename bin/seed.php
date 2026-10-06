#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Command\Seeder;
use App\Framework\DatabaseConnection;
use App\Framework\Env;

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');
$config = require __DIR__ . '/../config/app.php';

$pdo = DatabaseConnection::make($config['db']);

$seeder = new Seeder();
$seeder->seed($pdo);