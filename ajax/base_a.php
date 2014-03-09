<?php

/**
 * Mother class for all ajax
 */
abstract class Base_Ajax
{
    // Model
    static protected $model;

    // Main method, called by the router
    static public function launch()
    {
        // LSB for Model
        $modelName = str_replace( '_Ajax', '_Model', get_called_class());
        static::$model = new $modelName();

        // Launch main process
        static::process();
    }

    // Core method that does nothing here and need to be overwritten by children class
    static protected function process() {}
}
