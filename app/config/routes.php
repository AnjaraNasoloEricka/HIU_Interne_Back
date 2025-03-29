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