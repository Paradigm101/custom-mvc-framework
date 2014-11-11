<?php

/**
 * Mother class for all ajax controllers
 */
abstract class Base_Ajax_Controller {

    // Model
    static protected $model;

    // Data to send
    static private $answer;

    // Add answer data for client
    static protected function addAnswer( $key, $value ) {
        self::$answer[ $key ] = $value;
    }

    // Main method, called by the router
    static public function launch() {

        // LSB for Model
        $modelName = str_replace( '_Controller', '_Model', get_called_class());
        self::$model = new $modelName();

        // Answer data
        self::$answer = array();

        // Launch main process
        static::process();

        // Send answer to client
        self::sendAnswer();
    }

    // Core method that does nothing here and need to be overwritten by children class
    static protected function process() {}

    // Manage data to send back
    static private function sendAnswer() {

        // Using JSON
        header("content-type:application/json");

        // Converting and sending data
        echo json_encode(self::$answer);
    }
}
