<?php

// Base of all controller (for page, api and ajax)
// All attributes are static as controllers are always abstract
abstract class Base_LIB_Controller {

    // Model
    static protected $model;

    // What if no model file?
    static protected function getDefaultModel() {

        return 'Base_LIB_Model';
    }

    // View (more like answer for API/AJAX)
    static protected $view;

    // What if no view file?
    static protected function getDefaultView() {

        return 'Base_LIB_View';
    }

    // Entry point, called by the index:
    //      Set model, view
    //      Pass the relay to children
    //      Render view
    static public function launch() {

        // LSB for Model name, will based on inherited controller name
        $modelName = substr(get_called_class(), 0, -2) . '_M';

        // If specific model doesn't exists, take the generic one
        if ( !isClassExists($modelName )) {

            $modelName = static::getDefaultModel();
        }

        self::$model = new $modelName();

        // LSB for View name, will based on inherited controller name
        $viewName = substr(get_called_class(), 0, -2) . '_V';

        // If specific view doesn't exists, take the generic one
        if ( !isClassExists($viewName )) {

            $viewName = static::getDefaultView();
        }
        
        self::$view = new $viewName();
        
        static::pLaunch();
        
        self::$view->render();
    }

    // Core method: for children
    static protected function pLaunch() {}
}
