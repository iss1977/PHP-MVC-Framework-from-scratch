<?php 

namespace app\core;



class Application
{
    // we define a root directory for our app
    public static string $ROOT_DIR;
    // the router will be stored in Application
    public Router $router;
    public Request $request;
    public Response $response;

    public static Application $app;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath; // so we use always this directory in our project
        self::$app = $this; // ...so we can access the application from the router like this: Application::$app
        

        $this->request = new Request();
        $this->router = new Router($this->request);    
        $this->response = new Response();
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}