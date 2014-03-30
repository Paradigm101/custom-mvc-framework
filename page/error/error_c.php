<?php

abstract class Error_Page_Controller extends Base_Page_Controller {

    static private $message = 'Error message not initialized';

    static public function setMessage( $message = '' ) {

        static::$message = $message;
    }

    static protected function process() {

        static::$view->assign('message', static::$message);
    }
}
