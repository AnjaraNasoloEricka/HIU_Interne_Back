<?php

use app\controllers\ExampleController;
use app\controllers\CRUDController;

$exampleController = new ExampleController();
$router->get('/', [$exampleController, 'goToHomePage']);

$CRUD_Controller = new CRUDController();
$router->get('/readArticle', [$CRUD_Controller, 'crudArticle']);