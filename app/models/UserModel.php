<?php
namespace app\models;
use PDO;

class UserModel
{
    public $db;
    public $id_user;
    public $name;
    public $password;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function checkLogin($name,$password){
        $sql = "SELECT id_user from User where name = ? and password = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1,$name);
        $stmt->bindValue(2,$password);
        $stmt->execute();
        return $stmt->fetch();
    }
}