<?php


namespace app\core;

/**
 * How do flash messages work:
 * We set a message by ex in AuthController before redirect.
 * After redirect, in the constructor of Session, all flash messages will be marked to be removed.
 * After use in the request, they are removed in the destructor.
 */

/**  */
class Session
{
    protected const FLASH_KEY = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMessages=$_SESSION[self::FLASH_KEY] ??[]; // if no flash messages, take an empty array to avoid errors.
        foreach ($flashMessages as $key =>&$flashMessage){ // & = using as reference !!!!
            //mark all to be removed
            $flashMessage['remove']=true; // set remove tag - will be deleted on destruct.
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages; // if empty to avoid errors and to put the modified array ($flashMessages - set remove tag) back into $_SESSION

    }
    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function __destruct()
    {
        $flashMessages=$_SESSION[self::FLASH_KEY] ??[]; // if no flash messages, take an empty array to avoid errors.
        foreach ($flashMessages as $key =>&$flashMessage){ // & = using as reference !!!!
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages; // if empty to avoid errors and to put the modified array (removed messages) back into $_SESSION
    }
}