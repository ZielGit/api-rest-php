<?php

$arrayRoutes = explode("/",$_SERVER['REQUEST_URI']);

// El arrayRoutes esta basado en servidor virtual (siendo la base 0, 1 la primera ruaa y 2 una sub ruta)
// Cambiar segun la raiz de su proyecto
// echo "<pre>"; print_r($arrayRoutes); echo "<pre>";

if (count(array_filter($arrayRoutes)) == 0) {
    $json = array("detail" => "no encontrado");
    echo json_encode($json, true);
    return;
} else {
    if (count(array_filter($arrayRoutes)) == 1) {
        if (array_filter($arrayRoutes)[1] == "cursos") {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
                $courses = new CourseController();
                $courses->index();
            }
        }

        if (array_filter($arrayRoutes)[1] == "registro") {
            $json = array("detail" => "estas en la vista registro");
            echo json_encode($json, true);
            return;
        }
    }
}

?>