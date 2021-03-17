<?php

/**
 * MAIN 
 */

// this will autoload our classes using composer
 require_once __DIR__.'/../vendor/autoload.php';
 
 // to be able to use the Application class, we must import it with use :
 use app\core\Application;

 $app = new  Application();


$app->router->get('/', function(){
     return 'Hello World';
 });

 $app->router->get('/contact', function(){
    return 'Hello Contact.';
});


$app->run();
