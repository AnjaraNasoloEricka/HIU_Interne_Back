<?php

use app\controllers\ExampleController;
use app\controllers\UserController;

$exampleController = new ExampleController();
$userController = new UserController(Flight::app());

$router->get('/', [$exampleController, 'goToHomePage']);
$router->post('/user',[$userController, 'login']);
use app\controllers\CRUDController;

$exampleController = new ExampleController();
$router->get('/', [$exampleController, 'goToHomePage']);

$CRUD_Controller = new CRUDController();
$router->get('/readArticle', [$CRUD_Controller, 'crudArticle']);
