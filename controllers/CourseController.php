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
}

?>