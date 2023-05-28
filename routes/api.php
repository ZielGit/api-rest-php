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
            $json = array("detalle" => "estas en la vista cursos");
            echo json_encode($json, true);
            return;
        }

        if (array_filter($arrayRoutes)[3] == "registro") {
            $json = array("detalle" => "estas en la vista registro");
            echo json_encode($json, true);
            return;
        }
    }
}

?>