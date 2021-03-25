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
    public Database $db;

    public static Application $app;

    public Controller $controller;

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath; // so we use always this directory in our project
        self::$app = $this; // ...so we can access the application from the router like this: Application::$app
        

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']); // subarray "dn" will be only send
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}