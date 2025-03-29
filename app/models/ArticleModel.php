<?php
namespace app\models;
use PDO;
use Flight;
use DateTime;
use Exception;

class ArticleModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function delete($id_article) {
        $sql = "DELETE FROM article WHERE id_article = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $id_article, PDO::PARAM_INT);
    
        try {
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return ["succes" => "Article supprimé", "id_article" => $id_article];
                } else {
                    return ["erreur" => "Aucun article trouvé avec cet ID"];
                }
            }
        } catch (Exception $e) {
            return ["erreur" => "Erreur SQL : " . $e->getMessage()];
        }
    }
    
}