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
      
      // we found the route if we got here, so call the coresponding function. works also whithout return....
      return call_user_func($callback);

      
    }

    public function renderView($view){
      $layoutContent = $this->layoutContent();
      $viewContent = $this->renderOnlyView($view);
      
      $webPageContent = str_replace('{{content}}',$viewContent, $layoutContent); // search for {{content}} in $layoutContent and replace with $viewContent
      return $webPageContent;
    }

    protected function layoutContent(){
      // webpage output buffer
      ob_start();
      include_once Application::$ROOT_DIR . '/views/layouts/main.php';
      return ob_get_clean();// display and clear.
    }

    protected function renderOnlyView($view)
    {
      ob_start();
      include_once Application::$ROOT_DIR . "/views/$view.php";
      return ob_get_clean();
    }

    protected function renderContent($viewContent)
    {
      $layoutContent = $this->layoutContent();
      return str_replace('{{content}}', $viewContent , $layoutContent);
    }
 }