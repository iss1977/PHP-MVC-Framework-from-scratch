<?php

/**
 * MAIN 
 */

// this will autoload our classes using composer
require_once __DIR__.'/../vendor/autoload.php';
 
 // to be able to use the Application class, we must import it with use :

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

// Loading .env data
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__)); // the directory where the .env file is present.
$dotenv->load();


// Application configuration
$config = [
    'userClass' => \app\models\User::class, // We specify the User class name, because the 'core' should contain only reusable code.
    'db' => [
        'dsn' => $_ENV['DB_DNS'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new  Application(dirname(__DIR__), $config);


$app->router->get('/', [SiteController::class,'home']); // was $app->router->get('/', 'home');
$app->router->get('/contact', [SiteController::class,'contact']); //was  $app->router->get('/contact', 'contact');
$app->router->post('/contact', [SiteController::class,'handleContact']); // this will be passed to Router and it will call "call_user_func($callback);" that accepts also an array like  [SiteController::class,'handleContact']

$app->router->get('/login', [AuthController::class,'login']);
$app->router->post('/login', [AuthController::class,'login']);
$app->router->get('/register', [AuthController::class,'register']);
$app->router->post('/register', [AuthController::class,'register']);

$app->router->get('/logout', [AuthController::class,'logout']); // it should be a post request for security reasons. It will also work with get.

$app->run();
