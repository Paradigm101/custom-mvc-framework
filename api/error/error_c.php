<?php

// Manage wrong API requests
abstract class Error_API_C extends Base_API_C {

    static private $message = 'API error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process () {

        // Store answer
        static::$view->assign('error', static::$message);
    }
}
