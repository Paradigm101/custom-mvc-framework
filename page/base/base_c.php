<?php

/**
 * Mother class for all Page controllers
 */
abstract class Base_Page_Controller {

    // Model
    static protected $model;

    // View
    static protected $view;

    // Main method, called by the router
    static public function launch() {

        // LSB for Model
        $modelName = str_replace( '_Controller', '_Model', get_called_class());
        static::$model = new $modelName();

        // LSB for View
        $viewName = str_replace( '_Controller', '_View', get_called_class());
        static::$view = new $viewName();

        // Launch main process
        static::process();

        // Display the page
        static::$view->render();
    }

    // Core method that does nothing
    static protected function process() {}
}
