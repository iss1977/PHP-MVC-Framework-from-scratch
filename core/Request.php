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

    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    //helper function
    public function isGet(): bool
    {
        return $this->method()==='get';
    }
    public function isPost(): bool
    {
        return $this->method()==='post';
    }

    public function getBody(): array
    {
        $body =[];
        // sanitize data from GET or POST form data
        if($this->method()==='get'){
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET,$key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->method()==='post'){
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}