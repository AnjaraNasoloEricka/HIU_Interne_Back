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
}
