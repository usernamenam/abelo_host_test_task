<?php

declare(strict_types=1);

use App\Framework\DependencyContainer;
use App\Framework\Router;
use App\Http\Controller\CategoryController;
use App\Http\Controller\HomeController;
use App\Http\Controller\PostController;

return static function (Router $router, DependencyContainer $container): void {
    $homeController = $container->get(HomeController::class);
    $categoryController = $container->get(CategoryController::class);
    $postController = $container->get(PostController::class);

    $router->get('/', [$homeController, 'index']);
    $router->get('/category', [$categoryController, 'index']);
    $router->get('/post', [$postController, 'show']);
};
