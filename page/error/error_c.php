<?php

abstract class Error_PAG_C extends Base_PAG_C {

    static private $message = 'Error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process() {

        static::assign('message', static::$message);
    }
}
