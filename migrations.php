<?php

/**
 * Execute the migrations
 */

// this will autoload our classes using composer
require_once __DIR__ . '/vendor/autoload.php';

// to be able to use the Application class, we must import it with use :

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

// Loading .env data
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // the directory where the .env file is present.
$dotenv->load();


// Application configuration
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DNS'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new  Application(__DIR__, $config);

$app->db->applyMigrations();
