<?php

declare(strict_types=1);

namespace App\Framework;

use App\Http\Controller\CategoryController;
use App\Http\Controller\HomeController;
use App\Http\Controller\PostController;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\CategoryService;
use App\Service\PostService;
use Throwable;

final class Application
{
    private array $config;
    private View $view;
    private Router $router;
    private DependencyContainer $dependencyContainer;

    public function __construct(private readonly string $root)
    {
        Env::load($this->root . '/.env');
        $this->config = require $this->root . '/config/app.php';

        $this->view = new View($this->config['paths']);
        $this->initDependencyContainer();
        $this->initRouter();
    }

    private function initDependencyContainer(): void
    {
        $pdo = DatabaseConnection::make($this->config['db']);
        $categoryRepository = new CategoryRepository($pdo);
        $postRepository = new PostRepository($pdo);
        $categoryService = new CategoryService($categoryRepository, $postRepository);
        $postService = new PostService($postRepository);
        $dependencies = [
            HomeController::class => new HomeController($this->view, $categoryService),
            CategoryController::class => new CategoryController($this->view, $categoryService),
            PostController::class => new PostController($this->view, $postService),
        ];

        $this->dependencyContainer = new DependencyContainer($dependencies);
    }

    private function initRouter(): void
    {
        $router = new Router();
        $routes = require $this->root . '/src/Http/Route/web.php';
        $routes($router, $this->dependencyContainer);

        $this->router = $router;
    }

    public function run(): void
    {
        try {
            $response = $this->dispatchRoute();
            if ($response === null) {
                $response = $this->view->error(404, 'У нас нет такой страницы или она переехала.');
            }

            echo $response;
        } catch (Throwable $e) {
            echo $this->view->error(500, $e->getTraceAsString());
        }
    }

    private function dispatchRoute(): ?string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return $this->router->dispatch($method, $uri) ?? null;
    }
}
