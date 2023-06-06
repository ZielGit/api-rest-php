<?php

$arrayRoutes = explode("/",$_SERVER['REQUEST_URI']);

// El arrayRoutes esta basado en servidor virtual (siendo la base 0, 1 la primera ruaa y 2 una sub ruta)
// Cambiar segun la raiz de su proyecto
// echo "<pre>"; print_r($arrayRoutes); echo "<pre>";

if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
    $courses=new CourseController();
    $courses->index($_GET["page"]);
} else {
    if (count(array_filter($arrayRoutes)) == 0) {
        // Cuando no se hace ninguna petición a la API
        $json=array("detail" => "not found");
        echo json_encode($json, true);
        return;
    } else {
        // Cuando pasamos solo un índice en el array $arrayRoutes
        if (count(array_filter($arrayRoutes)) == 1) {
            // Cuando se hace peticiones desde courses
            if (array_filter($arrayRoutes)[1] == "courses") {
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST" ) {
                    // Capturar data
                    $data = array(
                        "title" => $_POST["title"],
                        "description" => $_POST["description"],
                        "instructor" => $_POST["instructor"],
                        "image" => $_POST["image"],
                        "price" => $_POST["price"]
                    );

                    //  echo "<pre>"; print_r($data); echo "<pre>";

                    $courses=new CourseController();
                    $courses->create($data);
                } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET" ) {
                    $courses = new CourseController();
                    $courses->index(null);
                }
            }

            // Cuando se hace peticiones desde register
            if (array_filter($arrayRoutes)[1] == "register") {
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST" ) {
                    $data = array(
                        "name" => $_POST["name"],
                        "last_name" => $_POST["last_name"],
                        "email" => $_POST["email"]
                    );
                    $customers = new CustomerController();
                    $customers->create($data);
                }
            }
        } else {
            if(array_filter($arrayRoutes)[1] == "courses" && is_numeric(array_filter($arrayRoutes)[2])){
                // Peticiones GET
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET" ) {
                    $course = new CourseController();
                    $course->show(array_filter($arrayRoutes)[2]);
                }

                // Peticiones PUT
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT" ) {
                    // Capturar data
                    $data = array();
                    parse_str(file_get_contents('php://input'), $data);

                    //echo "<pre>"; print_r($data); echo "<pre>";

                    //return;
                    $editCourse = new CourseController();
                    $editCourse->update(array_filter($arrayRoutes)[2], $data);
                }

                // Peticiones DELETE
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE") {
                    $deleteCourse = new CourseController();
                    $deleteCourse->delete(array_filter($arrayRoutes)[2]);
                }
            }
        }
    }
}