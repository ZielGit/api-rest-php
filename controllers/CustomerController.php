<?php

class CustomerController
{
    public function index()
    {
        $json = array("detalle" => "estas en la vista customer");
        echo json_encode($json, true);
        return;
    }
}

?>