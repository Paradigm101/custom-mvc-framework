<?php

// Manage wrong Ajax requests
abstract class Error_AJA_C extends Base_AJA_C {

    static private $message = 'Ajax error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process() {

        // Store answer
        static::$view->assign('error', static::$message);
    }
}
