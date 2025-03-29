<?php

namespace app\models;

use Flight;
use Exception;

class CrudModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($nomTable)
    {
        $sql = "select * from " . $nomTable;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}