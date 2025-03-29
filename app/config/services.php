<?php

use app\models\ArticleModel;
use app\models\UserModel;
use app\models\CrudModel;
use app\models\GeneraliseModel;
use app\models\CreateModel;

use flight\Engine;
use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use Tracy\Debugger;

/** 
 * @var array $config This comes from the returned array at the bottom of the config.php file
 * @var Engine $app
 */

// uncomment the following line for MySQL
$dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';

// uncomment the following line for SQLite
// $dsn = 'sqlite:' . $config['database']['file_path'];

// Uncomment the below lines if you want to add a Flight::db() service
// In development, you'll want the class that captures the queries for you. In production, not so much.
$pdoClass = Debugger::$showBar === true ? PdoQueryCapture::class : PdoWrapper::class;
$app->register('db', $pdoClass, [$dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null]);

// Got google oauth stuff? You could register that here
// $app->register('google_oauth', Google_Client::class, [ $config['google_oauth'] ]);

// Redis? This is where you'd set that up
// $app->register('redis', Redis::class, [ $config['redis']['host'], $config['redis']['port'] ]);


// MAPPING EXAMPLE : 
Flight::map('CreateModel', function () {
    return new CreateModel(Flight::db()); 
});

Flight::map('userModel',function () {
    return new UserModel(Flight::db());
});
Flight::map('CrudModel', function () {
    return new CrudModel(Flight::db());
});
Flight::map('GeneraliseModel', function () {
    return new GeneraliseModel(Flight::db());
});
Flight::map('articleModel', function () {
    return new ArticleModel(Flight::db());
});
