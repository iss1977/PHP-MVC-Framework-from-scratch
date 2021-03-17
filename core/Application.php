<?php 

namespace app\core;

class Application
{
    // the router will be stored in Application
    public Router $router;
    private Request $request;

    public function __construct()
    {
        // we don't have to specify more than that, because class Application and Router have the same namespace
        $this->request = new Request();
        $this->router = new Router($this->request);    
    }

    public function run()
    {
        $this->router->resolve();
    }
}