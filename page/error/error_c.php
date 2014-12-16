<?php

// Manage wrong page requests
abstract class Error_PAG_C extends Base_PAG_C {

    static private $message = 'Page error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    // Core method
    static protected function process() {

        // Store answer
        static::$view->assign('error', static::$message);
    }
}
