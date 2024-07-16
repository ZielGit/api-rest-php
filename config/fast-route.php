<?php

namespace Config;

use App\Controllers\MethodNotAllowedController;
use App\Controllers\NotFoundController;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Doctrine\ORM\EntityManager;
use function FastRoute\simpleDispatcher;

class FastRouter
{
    private array $routes = [];
    private array $group = [];

    public function group(string $prefix, Closure $callback)
    {
        $this->group[$prefix] = $callback;
    }

    public function addRoute(string $method, string $uri, array $controller)
    {
        $this->routes[] = [$method, $uri, $controller];
    }

    private function groupRoutes(RouteCollector $r)
    {
        foreach ($this->group as $prefix => $routes) {
            $r->addGroup($prefix, function (RouteCollector $r) use ($routes) {
                $routes($r);
            });
        }
    }

    public function run(EntityManager $entityManager)
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            if (!empty($this->group)) {
                $this->groupRoutes($r);
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

        $this->handle($routeInfo, $entityManager);
    }

    private function handle(array $routeInfo, EntityManager $entityManager)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                call_user_func_array([new NotFoundController, 'index'], []);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                call_user_func_array([new MethodNotAllowedController, 'index'], []);
                break;
            case Dispatcher::FOUND:
                [, [$controller, $method], $vars] = $routeInfo;
                $controllerInstance = new $controller($entityManager);
                call_user_func_array([$controllerInstance, $method], $vars);
                break;
        }
    }
}