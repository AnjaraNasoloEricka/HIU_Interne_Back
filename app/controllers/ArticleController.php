<?php

namespace app\controllers;

use Flight;
use app\models\ArticleModel;
use Flight\Engine;

class ArticleController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
    public function deleteArticle($id_article)
    {
        $response = Flight::articleModel()->delete($id_article);
        return  $this->app->json($response, 200, true, 'utf-8', JSON_PRETTY_PRINT);
    }
}
