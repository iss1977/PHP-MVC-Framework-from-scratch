<?php
/**
 * Router.
 * will be initiated in Application constructor
 */

 namespace app\core;

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
        // when we call this function it will save (register) the route to the array like this:
        // $routes = [
        //     'get' => [
        //         '/' => callack,
        //         '/contact' => other_cvallback
        //     ],
        //     'post'=> [
        //         '/' => callback ,
        //         etc
        //     ]
        // ];

        $this->routes['get'][$path] = $callback;
    }  


    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    // we call this function to resolve the current path. Example from $application->run()
    {
      $path = $this->request->getPath(); // will return the path without query part ex.: /users/hello
      $method = $this->request->getMethod(); // Returns 'get' or 'post'. In  this form we can use the value directly in the assoc array
      $callback = $this->routes[$method][$path] ?? false; // we can get the coresponding callback function from the routes array

      if($callback ===false){
        $this->response->setStatusCode(404);
        return $this->renderView('_404');
      }

      //if $callback is string, then we asume that we want to load a view. So we are calling renderView()...
      if(is_string($callback)){
        return $this->renderView($callback);
        
      }
      
      // if we get to this point, $callback can be an array (see index.php).
      if(is_array($callback)){
        $nameOfClass = $callback[0];
        $temporaryObject = new $nameOfClass();
        $callback[0] = $temporaryObject; // we prepare $callback to be an array with an instance on first element and the function name as second element.

      }
      
      // geeksforgeeks: Another way to use object 
      // $obj = new GFG(); 
      // call_user_func(array($obj, 'show')); 
      // and this is the way we use this.
      // it will call SiteController with the name of the function bioth from $callback array and pass as parameter $this->request.
      return call_user_func($callback, $this->request); //  works also whithout return....

      
    }

    /**
     * function renderView
     */
    public function renderView($view, $params=[]){
      $layoutContent = $this->layoutContent();
      $viewContent = $this->renderOnlyView($view, $params);
      
      $webPageContent = str_replace('{{content}}',$viewContent, $layoutContent); // search for {{content}} in $layoutContent and replace with $viewContent
      return $webPageContent;
    }

    protected function layoutContent(){
      // webpage output buffer
      ob_start();
      include_once Application::$ROOT_DIR . '/views/layouts/main.php';
      return ob_get_clean();// display and clear.
    }

    protected function renderOnlyView($view, $params)
    {
      foreach ($params as $key => $value) {
        $$key = $value; // if $key evaluates to "name" then we will have a variable $name with the value $value
      }

      ob_start();
      // the variables initiated in the foreach loop will be avaible 
      include_once Application::$ROOT_DIR . "/views/$view.php";
      return ob_get_clean();
    }

    protected function renderContent($viewContent)
    {
      $layoutContent = $this->layoutContent();
      return str_replace('{{content}}', $viewContent , $layoutContent);
    }
 }