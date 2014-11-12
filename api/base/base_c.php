<?php

/**
 * Mother class for all api controllers
 */
abstract class Base_API_C {

    // Model
    static protected $model;

    // Data to send
    static private $answer;

    // Add answer data for client
    static protected function setAnswer( $answer ) {

        self::$answer = $answer;
    }

    // Main method, called by the router
    static public function launch() {

        // LSB for Model
        $modelName = str_replace( '_C', '_M', get_called_class());
        self::$model = new $modelName();

        // Initialize Answer data
        self::$answer = 'OK';

        // Launch main process
        static::process();

        // Send answer to client
        static::sendAnswer();
    }

    // Core method that does nothing here and need to be overwritten by children class
    static protected function process() {

        Log_LIB::trace('[Base_API_C] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }

    // Manage data to send back
    static private function sendAnswer() {

        // Converting and sending data
        Log_LIB::show(ALL_EOL . self::$answer);
    }
}
