<?php

namespace App\Models;

use PDO;

class Course
{
    private $conn;
    private $table = 'courses';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function index()
    {
        $query = "SELECT courses.id, courses.title, courses.description, courses.instructor, courses.image, courses.price, courses.creator_id, customers.name, customers.last_name FROM courses INNER JOIN customers ON courses.creator_id = customers.id";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt = null;
    }

    public function create($data)
    {
        var_dump($data);
        $query = "INSERT INTO " . $this->table . " (title, description, instructor, image, price, creator_id, created_at, updated_at) VALUES (:title, :description, :instructor, :image, :price, :creator_id, :created_at, :updated_at)";
        $stmt = $this->conn->prepare($query);
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

    public function show($id)
    {
        $query = "SELECT courses.id, courses.title, courses.description, courses.instructor, courses.image, courses.price, courses.creator_id, customers.name, customers.last_name FROM courses INNER JOIN customers ON courses.creator_id = customers.id WHERE courses.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
        $stmt = null;
    }

    public function update($data)
    {
        $query = "UPDATE " . $this->table . " SET title = :title, description = :description, instructor = :instructor, image = :image, price = :price,updated_at = :updated_at WHERE id = :id";
        $stmt = $this->conn->prepare($query);
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

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if($stmt->execute()){
            return "ok";
        }else{
            print_r($this->conn->errorInfo());
        }

		$stmt = null;
    }
}