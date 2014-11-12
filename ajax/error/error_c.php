<?php

// Manage Ajax error
abstract class Error_AJA_C extends Base_AJA_C {

    static private $message = 'Error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process() {

        static::addAnswer('error', static::$message);
    }
}
