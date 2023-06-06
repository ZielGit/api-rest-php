<?php

require_once "controllers/CourseController.php";
require_once "controllers/CustomerController.php";
require_once "controllers/RouteController.php";
require_once "models/Course.php";
require_once "models/Customer.php";

$routes = new RouteController();
$routes->index();

?>