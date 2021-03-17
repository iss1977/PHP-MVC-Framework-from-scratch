<?php

/**
 * MAIN 
 */

// this will autoload our classes using composer
 require_once __DIR__.'/../vendor/autoload.php';
 
 // to be able to use the Application class, we must import it with use :
 use app\core\Application;

 $app = new  Application(dirname(__DIR__));


$app->router->get('/', 'home');
$app->router->get('/contact', 'contact');


$app->run();
