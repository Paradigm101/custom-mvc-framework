<?php

abstract class Error_Library
{
    static public function launch( $message = '' )
    {
        // Set error message
        Error_Controller::setMessage($message);

        // Launch error screen
        Error_Controller::launch();
    }
}
