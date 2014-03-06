<?php

abstract class Error_Controller extends Base_Controller
{
    static private $message;
    
    static public function setMessage( $message = '' ) {
        static::$message = $message;
    }

    static public function process()
    {
        static::$view->assign('message', static::$message);
    }
}
