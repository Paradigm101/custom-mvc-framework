<?php

/**
 * Mother class for all Page controllers
 */
abstract class Base_Page_Controller {

    // Model
    static protected $model;

    // View
    static private $view;

    // View setter: LSB
    static private function setView() {

        $viewName = str_replace( '_Controller', '_View', get_called_class());
        self::$view = new $viewName();
    }

    // View access wrapper
    static protected function assign( $name , $value ) {

        self::$view->assign( $name, $value );
    }

    // Main method, called by the router
    static public function launch() {

        // LSB for Model
        $modelName = str_replace( '_Controller', '_Model', get_called_class());
        static::$model = new $modelName();

        // LSB for View
        self::setView();

        // Launch main process
        static::process();

        // Display the page
        self::$view->render();
    }

    // Core method that does nothing
    static protected function process() {}
}
