<?php

namespace app\controllers;

use Flight;

class CRUDController
{
    public function __construct() {}

    public function crudArticle()
    {
        $article_list = Flight::CrudModel()->getAll("Article");
        $article_json = [];

        foreach($article_list as $article)
        {
            $article_info = 
            [
                "id_article" => $article["id_article"],
                "title" => $article["title"],
                "description" => $article["description"],
                "date_create" => $article["date_create"],
                "date_modification" => $article["date_modification"]
            ];
            $article_json[] = $article;
        }

        Flight::json_encode($article_json);
    }

    public function updateArticle($id)
    {
        $donnee_json = Flight::request()->getBody();
        $donnee = json_decode($donnee_json, true);

        $sql = "select id_article from Article where id_article = :idArticle";
        $stmt = Flight::db()->prepare($sql);
        $stmt->execute(["idArticle" => $id]);
        $id_article = $stmt->fetch();

        if($id_article)
        {
            Flight::GeneraliseModel()->update("Article", $donnee, ["id_article" => $id_article["id_article"]]);
            Flight::json(['status' => "ok", 'id_article' => ($id), 'message' => "Modification Avec Succès"]);
        }
        else
        {
            Flight::GeneraliseModel()->insertInto("Article", $donnee);
            Flight::json(['status' => "ok", 'id_article' => $id, 'message' => "Ajout Avec Succès"]);
        }
    }
}
