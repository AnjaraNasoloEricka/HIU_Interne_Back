<?php

namespace app\controllers;

use flight\Engine;
use app\models\CreateModel;
use Flight;


class CreateController
{
	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

    public function goToHomePage()
    {
        Flight::render('accueil');
    }

    public function createArticle()
    {
        $CreateModel = new CreateModel(Flight::db());
        $title = Flight::request()->data->titre;
        $description = Flight::request()->data->description;
        $date_create = Flight::request()->data->date_create;
        $date_modification = Flight::request()->data->date_modification;

        // $title = "Nouveau produit en vente";
        // $description = "Un article exclusif disponible dès aujourd'hui.";
        // $date_create = "2025-03-29";
        // $date_modification = "2025-03-29";        

        $creer = $CreateModel->create($title, $description, $date_create, $date_modification);
        $tab = 
        [
            'titre' => $title,
            'description' => $description,
            'date_create' => $date_create,
            'date_modification' => $date_modification
        ];

        if($creer){
            Flight::json($tab);
        } else{
            Flight::json(['erreur' => "tsy mandeha eh"]);
        }
    }
}

