<?php

namespace app\core;

class Request
{

    /**
     * returns the path from the current uri.
     * removes uri query when present.
     */
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/'; // if exist and not null .... otherwise it's the home path : '/'
        $questionMarkPosition = strpos($path,'?');
        $returnPath = substr($path, 0, $questionMarkPosition===false?strlen($path):$questionMarkPosition);
        return $returnPath;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody()
    {
        $body =[];
        // sanitize data from GET or POST form data
        if($this->getMethod()==='get'){
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET,$key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->getMethod()==='post'){
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}