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
}