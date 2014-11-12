<?php

abstract class Error_API_C extends Base_API_C {
    
    static private $message = 'Error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process () {
        
        static::setAnswer(static::$message);
    }
}
