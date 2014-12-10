<?php

// Base of all controller (for page, api and ajax)
abstract class Base_LIB_Controller {

    // Static Model, inherited controller is abstract
    static protected $model;

    // Entry point, called by the index:
    //      Set model
    //      Manage security
    //      Pass the relay to children
    static public function start() {

        // LSB for Model name, will based on inherited controller name
        // Remove _C for _M
        $modelName = substr(get_called_class(), 0, -2) . '_M';

        // If specific model doesn't exists, take the generic one
        if ( !isClassExists($modelName )) {

            self::$model = new Base_LIB_Model();
        }
        else {

            self::$model = new $modelName();
        }

        static::launch();
    }

    // Launch the controller, has to be overwritten
    static protected function launch() {

        Log_LIB::trace('[Base_LIB_Controller] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }
}
