<?php

/**
 * Mother class for all controllers
 */
abstract class Base_Controller
{
    // Model
    static protected $model;

    // View
    static protected $view;
    
    // User friendly error message
    static protected function getLastError()
    {
        if ( static::$model->getLastError() == MC_ERROR_STATUS_DUPLICATE_ENTRY )
            return 'Duplicate entry';
        
        return 'Unknow Error [' . static::$model->getLastDBError() . '] '
             . 'Query [' . static::$model->getLastQuery() . ']';
    }

    // Main method, called by the router
    static public function launch()
    {
        // LSB for model/view
        $modelName = str_replace( '_Controller', '_Model', get_called_class());
        static::$model = new $modelName();

        $viewName = str_replace( '_Controller', '_View', get_called_class());
        static::$view = new $viewName();

        // Launch main process
        static::process();

        static::$view->render();
    }

    // Core method that does nothing here and need to be overwritten by children class
    static public function process() {}
}
