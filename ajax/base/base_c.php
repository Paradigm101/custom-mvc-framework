<?php

/**
 * Mother class for all ajax controllers
 */
abstract class Base_AJA_C extends Base_LIB_Controller {

    // Data to send
    static private $answer;

    // Add answer data for client
    static protected function addAnswer( $key, $value ) {

        self::$answer[ $key ] = $value;
    }

    // Manage data to send back
    static private function sendAnswer() {

        // Using JSON
        header("content-type:application/json");

        // Converting and sending data
        echo json_encode(self::$answer);
    }

    // Set answer, pass the relay to children then send answer
    static protected function launch() {

        // Answer data
        self::$answer = array();

        // Launch main process
        static::process();

        // Send answer to client
        self::sendAnswer();
    }

    // Core method that does nothing here and need to be overwritten by children class
    static protected function process() {

        Log_LIB::trace('[Base_AJA_C] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }
}
