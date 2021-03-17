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

    
    public function __construct(Request $request)
    {
      $this->request = $request;
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

    public function resolve()
    // we call this function to resolve the current path. Example from $application->run()
    {
      $path = $this->request->getPath(); // will return the path without query part ex.: /users/hello
      $method = $this->request->getMethod(); // Returns 'get' or 'post'. In  this form we can use the value directly in the assoc array
      echo '<pre>';
      var_dump($this->routes);
      echo '</pre>';
      $callback = $this->routes[$method][$path] ?? false; // we can get the coresponding callback function from the routes array
      echo '<pre>'; echo '$callback is : '; var_dump($callback); echo '</pre>';
      if($callback ===false){
        echo "{$path} - Not found";
        exit;
      }
      // we found the route if we got here, so call the coresponding function
      echo call_user_func($callback);
    }
 }