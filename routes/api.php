<?php

$arrayRoutes = explode("/",$_SERVER['REQUEST_URI']);

echo "<pre>"; print_r($arrayRoutes); echo "<pre>";

if (count(array_filter($arrayRoutes)) == 2) {
    $json = array("detalle" => "no encontrado");
    echo json_encode($json, true);
    return;
} else {
    if (count(array_filter($arrayRoutes)) == 3) {
        if (array_filter($arrayRoutes)[3] == "cursos") {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
                $courses = new CourseController();
                $courses->index();
            }
        }

        if (array_filter($arrayRoutes)[3] == "registro") {
            $json = array("detalle" => "estas en la vista registro");
            echo json_encode($json, true);
            return;
        }
    }
}

?>