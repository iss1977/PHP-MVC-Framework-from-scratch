<?php

namespace  app\core;



use app\core\middlewares\BaseMiddleware;

class Controller
{

    public string $layout = 'main';

    /** @var BaseMiddleware[] */ // the  $middlewares  will be an array of objects that extend BaseMiddleware
    protected array $middlewares = [];

    public string $action=''; // this is current action (method) that is being called

    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params = [])
    {
        return Application::$app->router->renderView($view,$params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}