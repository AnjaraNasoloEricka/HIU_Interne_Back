<?php

use app\controllers\ExampleController;
use app\controllers\UserController;

$exampleController = new ExampleController();
$userController = new UserController(Flight::app());

$router->get('/', [$exampleController, 'goToHomePage']);
$router->post('/user',[$userController, 'login']);