<?php

/**
 * Mother class for all Page controllers
 *      inherited controllers are abstract => only static attributes and methodes
 */
abstract class Base_PAG_C {

    // Static Model, inherited controller is abstract
    static protected $model;

    // Static View, inherited controller is abstract
    static private $view;

    // View access wrapper
    static protected function assign( $name , $value ) {

        self::$view->assign( $name, $value );
    }

    // Entry point, called by the index
    static public function launch() {

        // LSB for Model name, will based on inherited controller name
        $modelName = str_replace( '_C', '_M', get_called_class());
        self::$model = new $modelName();

        // LSB for View name, will based on inherited controller name
        $viewName = str_replace( '_C', '_V', get_called_class());
        self::$view = new $viewName();

        // Launch main process
        static::process();

        // Display the page
        self::$view->render();
    }

    // Core method that does nothing and should be overwritten by children (if needed)
    static protected function process() {}
}
