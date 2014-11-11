<?php

abstract class Base_Table_Controller {

    static $initDone = false;

    static protected $model;

    // Initialize model and table parameters
    static protected function init() {

        // Can only be done once
        if ( !static::$initDone ) {
            
            static::$initDone = true;

            $modelName = str_replace( '_Controller', '_Model', get_called_class());
            static::$model = new $modelName();
        }
    }

    static public function createTable() {

        static::init();

        return static::$model->createTable();
    }
}
