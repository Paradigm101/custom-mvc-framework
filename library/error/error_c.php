<?php

// Manage error according to the type of request
abstract class Error_Library_Controller {

    static public function launch( $message = '', $request_type = REQUEST_TYPE_PAGE ) {

        switch ( $request_type ) {

            // Problem when loading a Page/Ajax/Api
            case REQUEST_TYPE_PAGE:
            case REQUEST_TYPE_AJAX:
            case REQUEST_TYPE_API:

                // Getting error management class according to request type
                $errorClass = 'Error_' . ucfirst(convertRequestTypeToName($request_type)) . '_Controller';
                
                // Launch error page for user
                $errorClass::setMessage($message);
                $errorClass::launch();
                break;

            // Other request type: log
            case REQUEST_TYPE_LIBRARY:
            case REQUEST_TYPE_TABLE:
            default:
                Log_Library_Controller::trace($message);
                break;
        }
    }
}
