<?php

// Sign-up Ajax controller
abstract class Signup_AJA_C extends Base_AJA_C {

    // Main process
    static protected function process() {

        // Retrieve parameters
        $email     = Url_LIB::getRequestParam('email');
        $username  = Url_LIB::getRequestParam('username');
        $password  = Url_LIB::getRequestParam('password');
        $password2 = Url_LIB::getRequestParam('password2');

        // Check same passwords
        if ( $password != $password2 ) {
            static::addAnswer('error',  'The passwords you entered are different.');
            return;
        }

        // Verify that email is not empty
        if ( !$email ) {

            static::addAnswer('error',  'Email has to be set.');
            return;
        }

        // Verify that username is not empty
        if ( !$username ) {

            static::addAnswer('error',  'Username has to be set.');
            return;
        }

        // Try and sign in the new user
        if ( ( $userId = static::$model->addUser( $email, $username, $password ) ) == 0 ) {

            // Retrieve error if needed
            switch( static::$model->getLastError() ) {

                // User already exists
                case BLM_ERROR_STATUS_DUPLICATE_ENTRY:
                    static::addAnswer('error',  'This email already exists in our system.');
                    break;

                // Unexpected error
                default:
                    static::addAnswer('error',  'Something wrong happened, try again later.');
            }
        }
        // Everthing went well, send userId
        else {
            static::addAnswer('userId', $userId);
        }

        // TBD: login (and reload page)
    }
}
