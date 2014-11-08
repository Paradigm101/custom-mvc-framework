<?php

// TBD
abstract class Error_Library_Controller {

    static public function launch( $message = '', $type = REQUEST_TYPE_PAGE ) {

        switch ( $type ) {

            case REQUEST_TYPE_AJAX:
                // Do stuff
                break;

            case REQUEST_TYPE_API:
                // Do stuff
                break;

            case REQUEST_TYPE_LIBRARY:
                // Do stuff
                break;

            case REQUEST_TYPE_PAGE:
                Error_Page_Controller::setMessage($message);
                Error_Page_Controller::launch();
                break;

            // Wrong type
            default:
                // Do stuff
                break;
        }
    }
}
