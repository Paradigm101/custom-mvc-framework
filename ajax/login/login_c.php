<?php

// Manage user log-in
abstract class Login_AJA_C extends Base_AJA_C {

    static protected function process() {

        $email    = Url_LIB::getRequestParam('email');
        $password = Url_LIB::getRequestParam('password');

        // Verify that email is not empty
        if ( !$email ) {
            
            static::$view->assign('error', 'Email has to be set.');
            return;
        }

        // Retrieving user id, No data: wrong user/password
        if ( ( $idUser = ( 0 + static::$model->checkPassword( $email, $password ) ) ) == null ) {

            static::$view->assign('error', 'Wrong email and/or password.');
            return;
        }

        // Start user session
        // TBD: Manage user already logged in
        Session_LIB::startUserSession( $idUser );
    }
}
