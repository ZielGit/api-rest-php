<?php
require __DIR__ . '/vendor/autoload.php';

use App\Controllers\CourseController;

use Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Configurar FastRoute
$dispatcher = simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/courses', [CourseController::class, 'index']);
    // $r->addRoute('GET', '/courses/{id:\d+}', [CourseController::class, 'show']);
});

// Obtener la ruta desde la URL
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Eliminar query string (?foo=bar) y decodificar URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
    case FastRoute\Dispatcher::FOUND:
        [,[$controller,$method], $vars] = $routeInfo;

        call_user_func_array([new $controller, $method], $vars);
        // ... call $handler with $vars
        break;
}
?>
