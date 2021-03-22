<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

// We setup the routes in index.php and call this class methods as : $app->router->get('/contact', [SiteController::class,'contact']);
class SiteController extends Controller
{

    /**
     * Render home page
     * using parameters
     */
    public function home()
    {
        $params = [
            'name'=> "My PHP Framework."
        ];
        // return Application::$app->router->renderView('home',$params); // this was the old form of calling
        return $this->render('home',$params);
    }



    public function contact()
    {
        //'Show contact form';
        return $this->render('contact');
    }


    /**
     * This function is used when we post a form from the contact page.
     */
    public function handleContact(Request $request)
    {
        $body = $request->getBody();

        foreach ($body as $key => $value) {
            echo $body[$key];
        }
        return 'Handling submitted data';
    }
}