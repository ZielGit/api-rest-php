<?php

namespace App\Models;

use PDO;

class Course
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function index($table1, $table2, $quantity, $from)
    {
        if ($quantity != null) {
            $stmt = $this->conn->prepare("SELECT $table1.id, $table1.title, $table1.description, $table1.instructor, $table1.image, $table1.price, $table1.creator_id, $table2.name, $table2.last_name FROM $table1 INNER JOIN $table2 ON $table1.creator_id = $table2.id LIMIT $from, $quantity");
        } else {
            $stmt = $this->conn->prepare("SELECT $table1.id, $table1.title, $table1.description, $table1.instructor, $table1.image, $table1.price, $table1.creator_id, $table2.name, $table2.last_name FROM $table1 INNER JOIN $table2 ON $table1.creator_id = $table2.id");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt = null;
    }

    public function create($table, $data)
    {
        $stmt = $this->conn->prepare("INSERT INTO $table(title, description, instructor, image, price, creator_id, created_at, updated_at) VALUES (:title, :description, :instructor, :image, :price, :creator_id, :created_at, :updated_at)");
        $stmt->bindParam(":title", $data["title"], PDO::PARAM_STR);
		$stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
		$stmt->bindParam(":instructor", $data["instructor"], PDO::PARAM_STR);
		$stmt->bindParam(":image", $data["image"], PDO::PARAM_STR);
		$stmt->bindParam(":price", $data["price"], PDO::PARAM_STR);
		$stmt->bindParam(":creator_id", $data["creator_id"], PDO::PARAM_STR);
		$stmt->bindParam(":created_at", $data["created_at"], PDO::PARAM_STR);
		$stmt->bindParam(":updated_at", $data["updated_at"], PDO::PARAM_STR);

        if($stmt->execute()){
			return "ok";
		}else{
			print_r($this->conn->errorInfo());
		}

		$stmt = null;
    }

    public function show($table1 ,$table2, $id)
    {
        $query = "SELECT $table1.id, $table1.title, $table1.description, $table1.instructor, $table1.image, $table1.price, $table1.creator_id ,$table2.name, $table2.last_name FROM $table1 INNER JOIN $table2 ON $table1.creator_id = $table2.id WHERE $table1.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt = null;
    }

    public function update($table , $data)
    {
        $stmt = $this->conn->prepare("UPDATE courses SET title = :title, description = :description, instructor = :instructor, image = :image, price = :price,updated_at = :updated_at WHERE id = :id");
        $stmt->bindParam(":id", $data["id"], PDO::PARAM_STR);
        $stmt->bindParam(":title", $data["title"], PDO::PARAM_STR);
		$stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
		$stmt->bindParam(":instructor", $data["instructor"], PDO::PARAM_STR);
		$stmt->bindParam(":image", $data["image"], PDO::PARAM_STR);
		$stmt->bindParam(":price", $data["price"], PDO::PARAM_STR);
		$stmt->bindParam(":updated_at", $data["updated_at"], PDO::PARAM_STR);

        if ($stmt->execute()) {
			return "ok";
		} else {
			print_r($this->conn->errorInfo());
		}

		$stmt = null;
    }

    public function delete($table, $id)
    {
        $stmt = $this->conn->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if($stmt->execute()){
            return "ok";
        }else{
            print_r($this->conn->errorInfo());
        }

		$stmt = null;
    }
}

?>