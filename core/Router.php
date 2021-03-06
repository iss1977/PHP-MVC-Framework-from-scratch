<?php
/**
 * Router.
 * will be initiated in Application constructor
 */

 namespace app\core;

 use app\core\exceptions\NotFoundException;

 class Router
 {
    protected array $routes = [];
    public Request $request;
    public Response $response;

    
    public function __construct(Request $request, Response $response)
    {
      $this->request = $request;
      $this->response = $response;
    }


    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }  


    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    // we call this function to resolve the current path. Example from $application->run()
    {
      $path = $this->request->getPath(); // will return the path without query part ex.: /users/hello
      $method = $this->request->method(); // Returns 'get' or 'post'. In  this form we can use the value directly in the assoc array
      $callback = $this->routes[$method][$path] ?? false; // we can get the corresponding callback function from the routes array

      if($callback ===false){
        //        $this->response->setStatusCode(404); // we gonna move this to Application -> run where we ahndle the exception and we set the status code that corresponds
        //        return $this->renderView('_404');
        throw new NotFoundException();
      }

      //if $callback is string, then we assume that we want to load a view. So we are calling renderView()...
      if(is_string($callback)){
        return $this->renderView($callback);
        
      }
      
      // if we get to this point, $callback can be an array (see index.php).
      if(is_array($callback)){

        /** @var Controller $controller */
        $controller =   new $callback[0](); // we create a new controller based on the name of the class stored on pos1 in the array $callback.
        Application::$app->setController($controller);
        $controller->action = $callback[1]; // set the current action in the controller so we can check if route is allowed for the current user.
        $callback[0] = $controller; // we prepare the $callback array to be sent to "call_user_func". At position 0 will be the object to be called the method on.

        foreach ($controller->getMiddlewares() as $middleware) // iterate the middleware objects stored in the $controller
            $middleware->execute(); // this will throw an exception if route not allowed.
      }



      // geeksforgeeks: Another way to use object 
      // $obj = new GFG(); 
      // call_user_func(array($obj, 'show')); 
      // and this is the way we use this.
      // it will call SiteController with the name of the function both from $callback array and pass as parameter $this->request.
      return call_user_func($callback, $this->request, $this->response); //  works also without return....

      
    }

     /**
      * function renderView
      * @param $view
      * @param array $params
      * @return false|string|string[]
      */
    public function renderView($view, $params=[]){
      $layoutContent = $this->layoutContent();
      $viewContent = $this->renderOnlyView($view, $params);
      
      $webPageContent = str_replace('{{content}}',$viewContent, $layoutContent); // search for {{content}} in $layoutContent and replace with $viewContent
      return $webPageContent;
    }

    protected function layoutContent(){
        $layout = Application::$app->defaultLayout;
        if (Application::$app->getController())
            $layout = Application::$app->getController()->layout;


      // webpage output buffer
      ob_start();
      include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
      return ob_get_clean();// display and clear.
    }

    protected function renderOnlyView($view, $params)
    {
      foreach ($params as $key => $value) {
        $$key = $value; // if $key evaluates to "name" then we will have a variable $name with the value $value
      }

      ob_start();
      // the variables initiated in the foreach loop will be available
      include_once Application::$ROOT_DIR . "/views/$view.php";
      return ob_get_clean();
    }

    protected function renderContent($viewContent)
    {
      $layoutContent = $this->layoutContent();
      return str_replace('{{content}}', $viewContent , $layoutContent);
    }
 }