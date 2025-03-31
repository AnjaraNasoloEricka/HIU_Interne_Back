<?php
use flight\Engine;
use flight\net\Router;
use app\controllers\CreateController;
use app\controllers\UserController;
use app\controllers\CRUDController;
use app\controllers\ArticleController;
use app\controllers\SentimentController;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$CreateController = new CreateController($app);
$userController = new UserController($app);
$articleController = new ArticleController($app);

// $router->get('/', [$CreateController, 'goToHomePage']);
$router->post('/', [$CreateController, 'createArticle']);
$router->get('/find/@id', [$CreateController, 'find_ById']);


$userController = new UserController(Flight::app());
$CRUD_Controller = new CRUDController();

$router->post('/', [$CreateController, 'createArticle']);
$router->post('/user',[$userController, 'login']);
$router->get('/readArticle', [$CRUD_Controller, 'crudArticle']);
$router->put('/updateArticle/@id', [$CRUD_Controller, 'updateArticle']);
$router->delete('/article/delete/@id', [$articleController, 'deleteArticle']);

$sentimentController = new SentimentController();
$router->post('/sentiment-controller', [$sentimentController, 'sentimentController']);
