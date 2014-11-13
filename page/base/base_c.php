<?php

/**
 * Mother class for all Page controllers
 *      inherited controllers are abstract => only static attributes and methodes
 */
abstract class Base_PAG_C extends Controller_Base_LIB {

    // Static View, inherited controller is abstract
    static private $view;

    // View access wrapper
    static protected function assign( $name , $value ) {

        self::$view->assign( $name, $value );
    }

    // Set view, pass the relay to children then render view
    static protected function launch() {

        // LSB for View name, will based on inherited controller name
        $viewName = str_replace( '_C', '_V', get_called_class());
        self::$view = new $viewName();

        // Launch main process
        static::process();

        // Display the page
        self::$view->render();
    }

    // Core method that does nothing and can be overwritten by children
    static protected function process() {}
}
