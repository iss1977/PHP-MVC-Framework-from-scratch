<?php


namespace app\core\exceptions;


class NotFoundException extends \Exception
{
    // overwrite the $code and $ message from the base class
    protected $code = 404;
    protected  $message = 'Page not found';
    /**
     * NotFoundException constructor.
     */
    public function __construct()
    {
    }
}