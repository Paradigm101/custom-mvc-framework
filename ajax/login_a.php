<?php

abstract class Login_Ajax extends Base_Ajax {

    static protected function process() {

        $email     = Urlparser_Library::getRequestParam('email');
        $password  = Urlparser_Library::getRequestParam('password');

        // Verify that email is not empty
        if ( !$email ) {
            static::addAnswer('error',  'Email has to be set.');
            return;
        }

        // Try and log-in the new user
        if ( ( static::$model->loginUser( $email, $password ) ) == null ) {

            static::addAnswer('error',  'Wrong email and/or password.');
        }
    }
}
