<?php
use flight\Engine;
use flight\net\Router;
use app\controllers\CreateController;

/** 
 * @var Router $router 
 * @var Engine $app
 */
$CreateController = new CreateController($app);

// $router->get('/', [$CreateController, 'goToHomePage']);
$router->get('/', [$CreateController, 'createArticle']);
use app\controllers\ExampleController;
use app\controllers\UserController;
use app\controllers\CRUDController;

$userController = new UserController(Flight::app());
$CRUD_Controller = new CRUDController();

$router->post('/user',[$userController, 'login']);

$router->get('/readArticle', [$CRUD_Controller, 'crudArticle']);

$router->put('/updateArticle/@id', [$CRUD_Controller, 'updateArticle']);

