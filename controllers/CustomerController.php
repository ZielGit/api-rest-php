<?php

class CustomerController
{
    public function create($data)
    {
        // echo "<pre>"; print_r($data); echo "<pre>";

        // Validar name
        if (isset($data["name"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $data["name"])) {
            $json = array(
                "status" => 404,
                "detail" => "error in name field allowed only letters in name"
            );
            echo json_encode($json, true);
            return;
        }

        // Validar last_name
        if (isset($data["last_name"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/' , $data["last_name"])) {
            $json = array(
                "status" => 404,
                "detail" => "error in the name field allowed only letters in the last name"
            );
            echo json_encode($json, true);
            return;
        }

        // Validar email
		if (isset($data["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $data["email"])) {
            $json = array(
                "status" => 404,
                "detail" => "error in the email field"
            );
            echo json_encode($json, true);
            return;
        }

        // Validar el email repetido
        $customers = Customer::index("customers");
        foreach ($customers  as $key => $value) {
            if ($value["email"] == $data["email"]) {
                $json = array(
                    "status" => 404,
                    "detail" => "the email is repeated"
                ); 
                echo json_encode($json, true);
                return;
            }
        }

        // Generar credenciales del cliente
        $customer_id= str_replace("$","c",crypt($data["name"].$data["last_name"].$data["email"] ,'$2a$07$afartwetsdAD52356FEDGsfhsd$'));
        $secret_key= str_replace("$","a",crypt($data["email"].$data["last_name"].$data["name"] ,'$2a$07$afartwetsdAD52356FEDGsfhsd$'));

        $data = array(
            "name" => $data["name"],
            "last_name" => $data["last_name"],
            "email" => $data["email"],
            "customer_id" => $customer_id,
            "secret_key" => $secret_key,
            "created_at" => date('Y-m-d h:i:s'),
            "updated_at" => date('Y-m-d h:i:s')
		);

        $create = Customer::create("customers", $data);

        if ($create == "ok") {
            $json = array(
                "status" => 404,
                "detail" => "your credentials are generated",
                "customer_id" => $customer_id,
                "secret_key" => $secret_key
            );
            echo json_encode($json, true);
            return;
        }
    }
}
