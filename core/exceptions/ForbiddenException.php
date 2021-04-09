<?php


namespace app\core\exceptions;


class ForbiddenException extends \Exception
{
    // overwriting the Base Exception with our error details.
    protected $code = 403;
    protected $message = 'You don\'t have permission to access this page.';
}