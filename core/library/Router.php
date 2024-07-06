<?php

namespace Core\Library;

use App\Controllers\MethodNotAllowedController;
use App\Controllers\NotFoundController;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    private array $routes = []; // Inicializar como un array vacío
    private array $group = [];  // Inicializar como un array vacío

    public function group(string $prefix, Closure $callback)
    {
        $this->group[$prefix] = $callback;
    }

    public function addRoute(string $method, string $uri, array $controller)
    {
        $this->routes[] = [$method, $uri, $controller];
    }

    private function group_routes(RouteCollector $r)
    {
        foreach ($this->group as $prefix => $routes) {
            $r->addGroup($prefix, function (RouteCollector $r) use ($routes) {
                $routes($r);
            });
        }
    }

    public function run()
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            if (!empty($this->group)) {
                $this->group_routes($r);
            }

            foreach ($this->routes as $route) {
                $r->addRoute(...$route);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        $this->handle($routeInfo);
    }

    private function handle(array $routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                call_user_func_array([new NotFoundController, 'index'], []);

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                call_user_func_array([new MethodNotAllowedController, 'index'], []);

                break;
            case Dispatcher::FOUND:
                [,[$controller,$method], $vars] = $routeInfo;

                call_user_func_array([new $controller, $method], $vars);
                // ... call $handler with $vars
                break;
        }
    }
}