<?php

/**
 * Mother class for all Page controllers
 *      inherited controllers are abstract => only static attributes and methodes
 */
abstract class Base_PAG_C extends Base_LIB_Controller {

    // Static View, inherited controller is abstract
    static private $view;

    // View access wrapper
    static protected function assign( $name , $value ) {

        self::$view->assign( $name, $value );
    }

    // Set view, pass the relay to children then render view
    static protected function launch() {

        // LSB for View name, will based on inherited controller name
        $viewName = substr(get_called_class(), 0, -2) . '_V';

        // If specific view doesn't exists, take the generic one
        if ( !isClassExists($viewName )) {

            self::$view = new Base_PAG_V( strtolower( str_replace( '_PAG_C', '', get_called_class() ) ) );
        }
        else {

            self::$view = new $viewName();
        }

        // Launch main process
        static::process();

        // Display the page
        self::$view->render();
    }

    // Core method that does nothing and can be overwritten by children
    static protected function process() {}
}
