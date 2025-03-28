<?php

namespace app\controllers;

use Flight;

class ExampleController
{
    public function __construct() {}
    public function goToHomePage()
    {
        Flight::render('accueil');
    }
}
