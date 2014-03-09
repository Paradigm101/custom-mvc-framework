<?php

abstract class Signup_Ajax extends Base_Ajax {
    
    static protected function process() {

        $email     = Urlparser_Library::getRequestParam('email');
        $username  = Urlparser_Library::getRequestParam('username');
        $password  = Urlparser_Library::getRequestParam('password');
        $password2 = Urlparser_Library::getRequestParam('password2');

        // Check same passwords TBD
        if ( $password != $password2 ) {
            Log_Library::trace('Different password');
            exit();
        }

        // Try and sign in the new user
        $error = '';
        if ( ($userId = static::$model->addUser( $email, $username, $password ) ) == 0 ) {

            switch(static::$model->getLastError()) {
                case BASE_ERROR_STATUS_DUPLICATE_ENTRY:
                    $error = 'email already exists in our database';
                    break;
                case BASE_ERROR_STATUS_NULL_VALUE:
                    $error = 'email or username is empty';
                    break;
                case BASE_ERROR_STATUS_UNKOWN:
                default:
                    $error = 'something wrong happened, try again later';
                    break;
            }
        }

        header("content-type:application/json");
        echo json_encode(array( 'userId' => $userId,
                                'error'  => $error ));
    }
}
