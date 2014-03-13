<?php

abstract class Signup_Ajax extends Base_Ajax {

    static protected function process() {

        $email     = Urlparser_Library::getRequestParam('email');
        $username  = Urlparser_Library::getRequestParam('username');
        $password  = Urlparser_Library::getRequestParam('password');
        $password2 = Urlparser_Library::getRequestParam('password2');
        
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
        if ( ($userId = static::$model->addUser( $email, $username, $password ) ) == 0 ) {

            // Retrieve error if needed
            switch(static::$model->getLastError()) {

                // User already exists
                case BASE_ERROR_STATUS_DUPLICATE_ENTRY:
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
    }
}
