<?php

class CourseController
{
    public function index($page)
    {
        // Validar credenciales del cliente
        $customers = Customer::index("customers");

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($customers as $key => $value) {
                if (
                    base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($value["customer_id"] . ":" . $value["secret_key"])
                ) {
                    if ($page != null) {
                        $quantity = 10;
                        $from = ($page - 1) * $quantity;
                        $courses = Course::index("courses", "customers", $quantity, $from);
                    } else {
                        $courses = Course::index("courses", "customers", null, null);
                    }
                    $json = array(
                        "status" => 200,
                        "total_records" => count($courses),
                        "detail" => $courses
                    );
                    echo json_encode($json, true);
                    return;
                }
            }
        }
    }

    public function create($data)
    {
        // Validar credenciales del cliente
        $customers = Customer::index("customers");

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($customers as $key => $valueCustomer) {
                if (
                    base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
                ) {
                    // Validar data
                    foreach ($data as $key => $valueDatos) {
                        if (isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)) {
                            $json = array(
                                "status" => 404,
                                "detail" => "Error in the " . $key . " field"
                            );
                            echo json_encode($json, true);
                            return;
                        }
                    }

                    // Validar que el title o la description no estén repetidos
                    $courses = Course::index("courses", "customers", null, null);

                    foreach ($courses as $key => $value) {
                        if ($value->title == $data["title"]) {
                            $json = array(
                                "status" => 404,
                                "detail" => "The title already exists in the database"
                            );
                            echo json_encode($json, true);
                            return;
                        }

                        if ($value->description == $data["description"]) {
                            $json = array(
                                "status" => 404,
                                "detail" => "The description already exists in the database"
                            );
                            echo json_encode($json, true);
                            return;
                        }
                    }

                    // Llevar data al modelo
                    $data = array(
                        "title" => $data["title"],
                        "description" => $data["description"],
                        "instructor" => $data["instructor"],
                        "image" => $data["image"],
                        "price" => $data["price"],
                        "creator_id" => $valueCustomer["id"],
                        "created_at" => date('Y-m-d h:i:s'),
                        "updated_at" => date('Y-m-d h:i:s')
                    );

                    $create = Course::create("courses", $data);

                    // Respuesta del modelo
                    if ($create == "ok") {
                        $json = array(
                            "status" => 200,
                            "detail" => "Registration successful, your course has been saved"
                        );
                        echo json_encode($json, true);
                        return;
                    }
                }
            }
        }
        $json = array(
            "detail" => "you are in the create view"
        );
        echo json_encode($json, true);
        return;
    }

    public function show($id)
    {
        // Validar credenciales del cliente
        $customers = Customer::index("customers");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($customers as $key => $valueCustomer) {
                if (
                    base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
                ) {
                    // Mostrar todos los courses
                    $curso = Course::show("courses", "customers", $id);
                    if (!empty($curso)) {
                        $json = array(
                            "status" => 200,
                            "detail" => $curso
                        );
                        echo json_encode($json, true);
                        return;
                    } else {
                        $json = array(
                            "status" => 200,
                            "total_records" => 0,
                            "details" => "There is no registered course"
                        );
                        echo json_encode($json, true);
                        return;
                    }
                }
            }
        }
    }

    public function update($id, $data)
    {
        // Validar credenciales del cliente
        $customers = Customer::index("customers");
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($customers as $key => $valueCustomer) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    "Basic " . base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
                ) {
                    // Validar data
                    foreach ($data as $key => $valueDatos) {
                        if (isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)) {
                            $json = array(
                                "status" => 404,
                                "detail" => "Error in the " . $key . " field"

                            );
                            echo json_encode($json, true);
                            return;
                        }
                    }

                    // Validar id creador
                    $curso = Course::show("courses", "customers", $id);
                    foreach ($curso as $key => $valueCourse) {
                        if ($valueCourse->creator_id == $valueCustomer["id"]) {
                            // Llevar data al modelo
                            $data = array(
                                "id" => $id,
                                "title" => $data["title"],
                                "description" => $data["description"],
                                "instructor" => $data["instructor"],
                                "image" => $data["image"],
                                "price" => $data["price"],
                                "updated_at" => date('Y-m-d h:i:s')
                            );

                            $update = Course::update("courses", $data);
                            if ($update == "ok") {
                                $json = array(
                                    "status" => 200,
                                    "detail" => "Registration successful, your course has been updated"
                                );
                                echo json_encode($json, true);
                                return;
                            } else {
                                $json = array(
                                    "status" => 404,
                                    "detail" => "You are not authorized to modify this course"
                                );
                                echo json_encode($json, true);
                                return;
                            }
                        }
                    }
                }
            }
        }
    }

    public function delete($id)
    {
        // Validar credenciales del cliente
        $customers = Customer::index("customers");

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            foreach ($customers as $key => $valueCustomer) {
                if (
                    "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
                    "Basic " . base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
                ) {
                    // Validar id creador
                    $curso = Course::show("courses", "customers", $id);
                    foreach ($curso as $key => $valueCourse) {
                        if ($valueCourse->creator_id == $valueCustomer["id"]) {
                            // Llevar data al modelo
                            $delete = Course::delete("courses", $id);
                            if ($delete == "ok") {
                                $json = array(
                                    "status" => 200,
                                    "detail" => "the course has been deleted"
                                );
                                echo json_encode($json, true);
                                return;
                            }
                        }
                    }
                }
            }
        }
    }
}

?>