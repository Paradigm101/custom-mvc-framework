<?php

// Manage error according to the type of request
abstract class Error_LIB {

    static public function process( $message = '', $requestTypeCode = REQUEST_TYPE_PAGE ) {

        switch ( $requestTypeCode ) {

            // Problem when loading a Page/Ajax/Api
            case REQUEST_TYPE_PAGE:
            case REQUEST_TYPE_AJAX:
            case REQUEST_TYPE_API:

                // Getting error management class according to request type
                $errorClass = 'Error_' . convertRequestCodeToClass($requestTypeCode) . '_C';

                // Launch error page for user
                $errorClass::setMessage($message);
                $errorClass::launch();
                break;

            // Other request type: log
            case REQUEST_TYPE_LIBRARY:
            default:
                Log_LIB::trace("[Error_LIB] message [$message] - request Code [$requestTypeCode]");
                break;
        }
    }
}
