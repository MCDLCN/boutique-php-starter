<?php
namespace App;
class Router
{
    private array $routes = [];

    public function get(string $path, array $action): void
    {
        $regex = preg_replace('/{(\w+)}/', '(?P<$1>[^/]+)', $path);
        $regex = '#^' . $regex . '$#';
        $this->routes['GET'][$regex] = $action;
    }

    public function post(string $path, array $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        // Dans dispatch() - avec preg_match
        foreach ($this->routes[$method] ?? [] as $regex => [$controller, $action]) {
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, fn($key)=>!is_int($key), ARRAY_FILTER_USE_KEY);
                $controllerInstance = new $controller();
                $controllerInstance->$action($params);
            }
        }
        http_response_code(404);
        echo 'Page not found';
    }
}