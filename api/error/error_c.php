<?php

abstract class Error_Api_Controller extends Base_Api_Controller {
    
    static private $message = 'Error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process () {
        
        static::setAnswer(static::$message);
    }
}
