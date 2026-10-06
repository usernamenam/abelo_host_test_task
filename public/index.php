<?php

declare(strict_types=1);

use App\Framework\Application;

require __DIR__ . '/../vendor/autoload.php';

(new Application(dirname(__DIR__)))->run();
