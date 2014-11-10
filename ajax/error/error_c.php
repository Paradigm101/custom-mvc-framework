<?php

// Manage Ajax error
abstract class Error_Ajax_Controller extends Base_Ajax_Controller {

    static private $message = 'Error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process() {

        static::addAnswer('error', static::$message);
    }
}
