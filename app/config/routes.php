<?php
use flight\Engine;
use flight\net\Router;
use app\controllers\CreateController;
use app\controllers\UserController;
use app\controllers\CRUDController;

/** 
 * @var Router $router 
 * @var Engine $app
 */
$CreateController = new CreateController($app);

// $router->get('/', [$CreateController, 'goToHomePage']);
$router->post('/', [$CreateController, 'createArticle']);


$userController = new UserController(Flight::app());
$CRUD_Controller = new CRUDController();

$router->post('/user',[$userController, 'login']);

$router->get('/readArticle', [$CRUD_Controller, 'crudArticle']);
