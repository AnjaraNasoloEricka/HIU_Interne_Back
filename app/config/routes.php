<?php
use flight\Engine;
use flight\net\Router;
use app\controllers\CreateController;
use app\controllers\UserController;
use app\controllers\CRUDController;
use app\controllers\ArticleController;

/** 
 * @var Router $router 
 * @var Engine $app
 */
$CreateController = new CreateController($app);

$userController = new UserController($app);
$articleController = new ArticleController($app);
$CRUD_Controller = new CRUDController();

$router->post('/', [$CreateController, 'createArticle']);
$router->post('/user',[$userController, 'login']);
$router->get('/readArticle', [$CRUD_Controller, 'crudArticle']);
$router->delete('/article/delete/@id', [$articleController, 'deleteArticle']);
