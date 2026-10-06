<?php

declare(strict_types=1);

namespace App\Framework;

class Router
{
    /** @var list<array{method: string, path: string, handler: callable(): string}> */
    private array $routes = [];

    /** @param callable(): string $handler */
    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => rtrim($path, '/') ?: '/',
            'handler' => $handler,
        ];
    }

    /** @param callable(): string $handler */
    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function dispatch(string $method, string $uri): ?string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                return ($route['handler'])();
            }
        }

        return null;
    }
}
