<?php

namespace App\Models;

use PDO;

class Customer
{
    private $conn;
    private $table = 'customers';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Mostrar todos los registros
    public function index()
    {
        $query = "SELECT * FROM ". $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt = null;
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . " (name, last_name, email, customer_id, secret_key, created_at, updated_at) VALUES (:name, :last_name, :email, :customer_id, :secret_key, :created_at, :updated_at)";
        $stmt = $this->conn->prepare($query);
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
			print_r($this->conn->errorInfo());
		}

		$stmt = null;
    }
}