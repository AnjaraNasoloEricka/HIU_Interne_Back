<?php

namespace app\controllers;

use Flight;
use app\models\UserModel;
use Flight\Engine;

class UserController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
    public function login()
    {
        $name = Flight::request()->data['name'];
        $password = Flight::request()->data['password'];
        $id_user = Flight::userModel()->checkLogin($name, $password);

        if ($id_user) {
            $response = ["id_utilisateur" => $id_user['id_user'], "succes" => "ok"];
        } else {
            $response = ["erreur" => "Mot de passe ou nom d'utilisateur erroné"];
        }
        return  $this->app->json($response, 200, true, 'utf-8', JSON_PRETTY_PRINT);
    }
}
