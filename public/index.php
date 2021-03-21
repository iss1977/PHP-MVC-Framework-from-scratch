<?php
session_start();

$number = 0;

$_SESSION["number"] = $_SESSION["number"]?? 0;


/**
 * MAIN 
 */

// this will autoload our classes using composer
 require_once __DIR__.'/../vendor/autoload.php';
 
 // to be able to use the Application class, we must import it with use :

use app\controllers\SiteController;
use app\core\Application;

 $app = new  Application(dirname(__DIR__));


$app->router->get('/', 'home');
$app->router->get('/contact', [SiteController::class,'contact']); //was  $app->router->get('/contact', 'contact');

$app->router->post('/contact', [SiteController::class,'handleContact']); // this will be passed to Router and it will call "call_user_func($callback);" that accepts also an array like  [SiteController::class,'handleContact']


$app->run();
