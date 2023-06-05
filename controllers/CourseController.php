<?php

class CourseController
{
    public function index()
    {
        $json = array("detalle" => "estas en la vista cursos");
        echo json_encode($json, true);
        return;
    }
}

?>