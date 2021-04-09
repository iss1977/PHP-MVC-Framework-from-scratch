<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile'])); // set up Middleware with restrictions only for the 'profile' method.
    }

    /** $request is used to see if it's a POST request, $response is used for redirect */
    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if ($request->isPost()){
            $loginForm->loadData($request->getBody()); // puts the data from the request into object LoginForm
            if ($loginForm->validate() && $loginForm->login()){
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login',[
            'model'=> $loginForm
        ]);
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
            $user = new User();
            $user->loadData($request->getBody());

            if($user->validate() && $user->save()){
                Application::$app->session->setFlash('success','Thank you for registering.');
                Application::$app->response->redirect('/');
                exit;
            }

            // if we arrive here, there are errors and the form did not pass.
            return $this->render('register', [
                'model' => $user
            ]);

            return 'Handling form data';
        }
        // It's a GET request
        return $this->render('register',[
            'model' => new User()
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }

    public function profile()
    {
        return $this->render('profile');
    }

}