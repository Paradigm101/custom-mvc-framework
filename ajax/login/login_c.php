<?php

abstract class Login_Ajax_Controller extends Base_Ajax_Controller {

    static protected function process() {

        $email     = Urlparser_Library_Controller::getRequestParam('email');
        $password  = Urlparser_Library_Controller::getRequestParam('password');

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
