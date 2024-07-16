<?php
require __DIR__ . '/vendor/autoload.php';

use Config\Database;
use Config\FastRouter;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Obtener el EntityManager
$entityManager = Database::getEntityManager();

$router = new FastRouter;

// Cargar las rutas API y web
$apiRoutes = require __DIR__ . '/routes/api.php';
$webRoutes = require __DIR__ . '/routes/web.php';

// Agrupar las rutas API con el prefijo /api
$router->group('/api', $apiRoutes);

// Cargar las rutas web
$webRoutes($router);

// Ejecutar el enrutador y pasar el EntityManager
$router->run($entityManager);