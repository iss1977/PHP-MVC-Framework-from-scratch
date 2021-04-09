<?php


namespace app\core\middlewares;


/** Base class for our middlewares */
abstract class BaseMiddleware
{
    abstract public function execute();
}