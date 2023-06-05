<?php
require_once "controllers/RouteController.php";
require_once "controllers/CourseController.php";

$routes = new RouteController();
$routes->index();
?>