<?php

use App\Controllers\Api\AuthController;
use App\Controllers\Api\CourseController;
use App\Controllers\Api\CustomerController;

return function ($router) {
    // Auth
    $router->addRoute('POST', '/auth/login', [AuthController::class, 'login']);
    $router->addRoute('POST', '/auth/register', [AuthController::class, 'register']);
    $router->addRoute('POST', '/auth/logout', [AuthController::class, 'logout']);

    // Customers
    $router->addRoute('GET', '/customers', [CustomerController::class, 'index']);
    $router->addRoute('POST', '/customers', [CustomerController::class, 'create']);
    $router->addRoute('GET', '/customers/{id:[0-9]+}', [CustomerController::class, 'show']);
    $router->addRoute('PUT', '/customers/{id:[0-9]+}', [CustomerController::class, 'update']);
    $router->addRoute('DELETE', '/customers/{id:[0-9]+}', [CustomerController::class, 'delete']);

    // Courses
    $router->addRoute('GET', '/courses', [CourseController::class, 'index']);
    $router->addRoute('POST', '/courses', [CourseController::class, 'create']);
    $router->addRoute('GET', '/courses/{id:[0-9]+}', [CourseController::class, 'show']);
    $router->addRoute('PUT', '/courses/{id:[0-9]+}', [CourseController::class, 'update']);
    $router->addRoute('DELETE', '/courses/{id:[0-9]+}', [CourseController::class, 'delete']);
};