<?php

abstract class Error_Library_Controller {

    static public function launch( $message = '', $type = 'page' ) {

        switch ( $type ) {

            case 'ajax':
                // Do stuff
                break;

            case 'api':
                // Do stuff
                break;

            case 'page':
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
