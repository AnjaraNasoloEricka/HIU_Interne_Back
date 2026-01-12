<?php
namespace app\models;
use PDO;
use Flight;
use DateTime;
class CreateModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($title, $description, $date_create, $date_modification){
        $sql = "INSERT INTO Article (title, description, date_create, date_modification) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$title, $description, $date_create, $date_modification]);
        return $this->db->lastInsertId();
    }

    public function findById($id){
        $sql = "SELECT * FROM Article WHERE id_article = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}