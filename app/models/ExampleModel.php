<?php
namespace app\models;
use PDO;
use Flight;
use DateTime;
class UserModel extends BaseModel
{
    public $id;
    public $username;
    public $pwd;

    public function __construct($db)
    {
        parent::__construct($db);
    }


    public function checkUser($username,$mdp){
        $sql = "SELECT id from td3_User where username = ? and pwd = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1,(string)$username);
        $stmt->bindValue(2,(string)$mdp);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getUsername($id){
        $sql = "SELECT username from td3_User where id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1,(string)$id);
        $stmt->execute();
        return $stmt->fetch();
    }

    

}