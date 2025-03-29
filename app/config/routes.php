<?php

use app\controllers\ExampleController;
use app\controllers\UserController;
use app\controllers\CRUDController;

$exampleController = new ExampleController();
$userController = new UserController(Flight::app());
$exampleController = new ExampleController();
$CRUD_Controller = new CRUDController();

$router->get('/', [$exampleController, 'goToHomePage']);
$router->post('/user',[$userController, 'login']);

$router->get('/', [$exampleController, 'goToHomePage']);

$router->get('/readArticle', [$CRUD_Controller, 'crudArticle']);
