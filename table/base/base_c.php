<?php

// Table controller; children are now non-abstract to initialize model without interfering
abstract class Base_Table_Controller {

    // Remove direct access from inherited controller which is basically a useless wrapper
    //      kept for homogeneity purpose: intersystem access is done through controllers!
    private $model;

    // Initialize model and table parameters
    public function __construct() {

        // LSB model
        $modelName = str_replace( '_Controller', '_Model', get_called_class());
        $this->model = new $modelName();
    }

    // Create a table in DB
    public function createTable() {

        // Model is doing the work
        return $this->model->createTable();
    }

    // Delete a table in DB
    public function deleteTable() {

        // Model is doing the work
        return $this->model->deleteTable();
    }
}
