<?php

use app\controllers\ExampleController;
$exampleController = new ExampleController();

$router->get('/', [$exampleController, 'goToHomePage']);