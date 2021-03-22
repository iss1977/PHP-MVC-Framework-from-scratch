<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class AuthController extends Controller
{
    public function login()
    {
        $this->setLayout('auth');
        $this->render('login');
    }

    /**
     * This method will handle the GET and POST requests
     */
    public function register(Request $request) // Request parameter is added in Router : call_user_func($callback, $this->request);
    {
        $this->setLayout('auth');
        if($request->isPost()){
            return 'Handling form data';
        }
        return $this->render('register');
    }

}