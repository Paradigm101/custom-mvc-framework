<?php

// Table controller
abstract class Base_Table_Controller {

    // Internal process
    static private $initDone = false;

    // Remove direct access from inherited controller which is basically a useless wrapper
    //      kept for homogeneity purpose: intersystem access is done through controllers!
    static private $model;

    // Initialize model and table parameters
    static private function init() {

        // Can only be done once
        if ( !self::$initDone ) {

            self::$initDone = true;

            // LSB model
            $modelName = str_replace( '_Controller', '_Model', get_called_class());
            self::$model = new $modelName();
        }
    }

    // Create a table in DB
    static public function createTable() {

        // Always try to initialize the table model before doing the work
        self::init();

        // Do the work ... well obv model is doing the work
        return self::$model->createTable();
    }
}
