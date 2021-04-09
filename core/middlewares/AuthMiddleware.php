<?php


namespace app\core\middlewares;


use app\core\Application;
use app\core\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions=[];

    /**
     * AuthMiddleware constructor.
     * @param array $actions
     */
    public function __construct(array $actions=[])
    {
        $this->actions = $actions;
    }

    /** This method will throw an error if the user is not allowed to visit the current route. We will catch it in Application->run() */
    public function execute()
    {
        if(Application::isGuest()){
            if( empty($this->actions) || in_array(Application::$app->getController()->action, $this->actions)){ // if no actions definded = protect all, or, if not empty then check if route is protected (contained in array $this->actions)
                throw new ForbiddenException();
            }
        }
    }
}