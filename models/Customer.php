<?php

require_once "connection.php";

class Customer
{    
    // Mostrar todos los registros
    static public function index($table)
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt = null;
    }

    static public function create($table, $data)
    {
        $stmt = Connection::connect()->prepare("INSERT INTO $table(name, last_name, email, customer_id, secret_key, created_at, updated_at) VALUES (:name, :last_name, :email, :customer_id, :secret_key, :created_at, :updated_at)");
       	$stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
		$stmt->bindParam(":last_name", $data["last_name"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
		$stmt->bindParam(":customer_id", $data["customer_id"], PDO::PARAM_STR);
		$stmt->bindParam(":secret_key", $data["secret_key"], PDO::PARAM_STR);
		$stmt->bindParam(":created_at", $data["created_at"], PDO::PARAM_STR);
		$stmt->bindParam(":updated_at", $data["updated_at"], PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
			print_r(Connection::connect()->errorInfo());
		}

		$stmt = null;
    }
}

?>