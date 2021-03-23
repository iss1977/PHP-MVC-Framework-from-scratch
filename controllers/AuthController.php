<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;

class AuthController extends Controller
{
    public function login()
    {
        $this->setLayout('auth');
        $this->render('login');
    }


    /**
     * THis method handles the POST request with the form data and GET request to render the page.
     * @param Request $request
     * @return false|string|string[]
     */
    public function register(Request $request) // Request parameter is added in Router : call_user_func($callback, $this->request);
    {
        $this->setLayout('auth');
        // It's a POST request
        if($request->isPost()){
            $registerModel = new RegisterModel();
            $registerModel->loadData($request->getBody());

            if($registerModel->validate() && $registerModel->register()){
                return 'Success';
            }
            echo '<pre>';
            var_dump($registerModel->errors);
            echo '</pre>';


            // if we arrive here, there are errors and the form did not pass.
            return $this->render('register', [
                'model' => $registerModel
            ]);

            return 'Handling form data';
        }
        // It's a GET request
        return $this->render('register');
    }

}