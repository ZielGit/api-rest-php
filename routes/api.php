<?php

use App\Controllers\Api\CourseController;

return function ($router) {
    $router->addRoute('GET', '/courses', [CourseController::class, 'index']);
    $router->addRoute('GET', '/courses/{id:[0-9]+}', [CourseController::class, 'show']);
    $router->addRoute('POST', '/courses', [CourseController::class, 'create']);
    $router->addRoute('PUT', '/courses/{id:[0-9]+}', [CourseController::class, 'update']);
    $router->addRoute('DELETE', '/courses/{id:[0-9]+}', [CourseController::class, 'delete']);
};