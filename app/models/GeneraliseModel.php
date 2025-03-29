<?php

namespace app\models;

use Flight;
use Exception;

class GeneraliseModel {

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

    public function getAllBy($nomTable, $conditions = [])
    {
        // Construire la requête de base
        $sql = "SELECT * FROM " . $nomTable;

        // Ajouter les conditions si elles existent
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $clauses = [];
            foreach ($conditions as $column => $value) {
                $clauses[] = $column . " = :" . $column;
            }
            $sql .= implode(" AND ", $clauses);
        }

        $stmt = $this->db->prepare($sql);

        // Lier les paramètres
        foreach ($conditions as $column => $value) {
            $stmt->bindValue(':' . $column, $value);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function insertInto($nomTable, $data)
    {
        // Construction des colonnes et des marqueurs pour la requête SQL
        $colonnes = implode(", ", array_keys($data));
        $marqueurs = implode(", ", array_map(fn($col) => ":" . $col, array_keys($data)));

        // Création de la requête SQL
        $sql = "INSERT INTO " . $nomTable . " (" . $colonnes . ") VALUES (" . $marqueurs . ")";
        $stmt = $this->db->prepare($sql);

        // Liaison des valeurs avec les marqueurs
        foreach ($data as $col => $val) {
            $stmt->bindValue(":" . $col, $val);
        }

        // Exécution de la requête
        return $stmt->execute();
    }

    public function deleteFrom($nomTable, $conditions)
    {
        // Construction de la partie WHERE de la requête
        $clauses = [];
        foreach ($conditions as $col => $val) {
            $clauses[] = $col . " = :" . $col; // Création des conditions dynamiques
        }
        $whereClause = implode(" AND ", $clauses);

        // Création de la requête SQL
        $sql = "DELETE FROM " . $nomTable . " WHERE " . $whereClause;
        $stmt = $this->db->prepare($sql);

        // Liaison des paramètres
        foreach ($conditions as $col => $val) {
            $stmt->bindValue(":" . $col, $val);
        }

        // Exécution de la requête
        return $stmt->execute();
    }

    public function getById($nomTable, $conditions)
    {
        // Construction de la partie WHERE de la requête
        $clauses = [];
        foreach ($conditions as $col => $val) {
            $clauses[] = $col . " = :" . $col; // Création des conditions dynamiques
        }
        $whereClause = implode(" AND ", $clauses);

        // Création de la requête SQL
        $sql = "SELECT * FROM " . $nomTable . " WHERE " . $whereClause;
        $stmt = $this->db->prepare($sql);

        // Liaison des paramètres
        foreach ($conditions as $col => $val) {
            $stmt->bindValue(":" . $col, $val);
        }

        // Exécution de la requête
        $stmt->execute();

        // Retourne une ligne unique
        return $stmt->fetch();
    }

    public function update($nomTable, $data, $conditions)
    {
        // Construction des colonnes à mettre à jour
        $updateFields = [];
        foreach ($data as $col => $val) {
            $updateFields[] = $col . " = :" . $col;
        }
        $updateClause = implode(", ", $updateFields);

        // Construction de la partie WHERE de la requête
        $whereFields = [];
        foreach ($conditions as $col => $val) {
            $whereFields[] = $col . " = :cond_" . $col;
        }
        $whereClause = implode(" AND ", $whereFields);

        // Création de la requête SQL
        $sql = "UPDATE " . $nomTable . " SET " . $updateClause . " WHERE " . $whereClause;
        $stmt = $this->db->prepare($sql);

        // Liaison des valeurs pour SET
        foreach ($data as $col => $val) {
            $stmt->bindValue(":" . $col, $val);
        }

        // Liaison des valeurs pour WHERE
        foreach ($conditions as $col => $val) {
            $stmt->bindValue(":cond_" . $col, $val);
        }

        // Exécution de la requête
        return $stmt->execute();
    }

    // Fonction générale d'importation CSV
    public function importCsv($tableName, $columns, $csvFilePath)
    {
        // Ouvrir le fichier CSV
        $file = fopen($csvFilePath, 'r');
        if (!$file) {
            throw new Exception("Impossible d'ouvrir le fichier CSV.");
        }

        $this->db->beginTransaction(); // Commencer une transaction

        try {
            while (($row = fgetcsv($file, 1000, ",")) !== false) {
                // Générer une requête INSERT dynamique
                $placeholders = implode(',', array_fill(0, count($columns), '?'));
                $sql = "INSERT INTO $tableName (" . implode(',', $columns) . ") VALUES ($placeholders)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($row); // Insérer les données
            }
            $this->db->commit(); // Valider la transaction
        } catch (Exception $e) {
            $this->db->rollBack(); // Annuler la transaction en cas d'erreur
            throw new Exception("Erreur lors de l'importation : " . $e->getMessage());
        } finally {
            fclose($file); // Fermer le fichier
        }
    }
}