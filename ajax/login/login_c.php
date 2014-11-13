<?php

abstract class Login_AJA_C extends Base_AJA_C {

    static protected function process() {

        $email     = Url_Manager_LIB::getRequestParam('email');
        $password  = Url_Manager_LIB::getRequestParam('password');

        // Verify that email is not empty
        if ( !$email ) {
            static::addAnswer('error', 'Email has to be set.');
            return;
        }

        // TBD: Manage user already logged in

        // Retrieving user id, No data: wrong user/password
        if ( ( $id_user = static::$model->checkPassword( $email, $password ) ) == null ) {

            static::addAnswer('error', 'Wrong email and/or password.');
            return;
        }

        // Register session
        static::$model->storeSession( $id_user, session_id() );
    }
}
