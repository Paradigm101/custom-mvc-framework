<?php

// Base of all controller (for page, api and ajax)
abstract class Controller_Base_LIB {

    // Static Model, inherited controller is abstract
    static protected $model;

    // Entry point, called by the index:
    //      Set model
    //      Manage security
    //      Pass the relay to children
    static public function start() {

        // LSB for Model name, will based on inherited controller name
        $modelName = str_replace( '_C', '_M', get_called_class());
        self::$model = new $modelName();

        static::launch();
    }

    // Launch the controller, has to be overwritten
    static protected function launch() {

        Log_LIB::trace('[Base_API_C] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }
}
