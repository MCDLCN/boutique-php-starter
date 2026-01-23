<?php

namespace App;

use RuntimeException;

class Router
{
    /**
     * @var array<string, array<string, array{0: class-string, 1: string}>>
     */
    private array $routes = [];

    /**
     * Summary of get
     * @param array{
     * 0: class-string,
     * 1:string} $action
     */
    public function get(string $path, array $action): void
    {
        $regex = preg_replace('/{(\w+)}/', '(?P<$1>[^/]+)', $path);
        $regex = '#^' . $regex . '$#';
        $this->routes['GET'][$regex] = $action;
    }

    /**
     * Summary of post
     * @param array{
     * 0: class-string,
     * 1:string} $action
     */
    public function post(string $path, array $action): void
    {
        $regex = preg_replace('/{(\w+)}/', '(?P<$1>[^/]+)', $path);
        $regex = '#^' . $regex . '$#';
        $this->routes['POST'][$regex] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        if ($path === false || $path === null) {
            throw new RuntimeException('Uri wrong');
        }
        $found = false;
        // Dans dispatch() - avec preg_match
        foreach ($this->routes[$method] ?? [] as $regex => [$controller, $action]) {
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, fn ($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                $controllerInstance = new $controller();
                $controllerInstance->$action($params);
                $found = true;
            }

        }
        if (!$found) {
            http_response_code(404);
            echo 'Page not found';
        }
    }
}
