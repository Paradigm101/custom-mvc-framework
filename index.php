<?php

/**
 * Unique entry point for the whole website
 */
// Globals
//-------------
require 'config.php';

// Autoload
//      className = <requestName>_<requestType>_<classType>
//      requestName can have underscores
//---------
spl_autoload_register( function ( $className ) {

    // Parse out class name to retrieve file and class
    $exploded    = explode('_', $className);

    $classType   = strtolower( array_pop($exploded) );
    $requestType = strtolower( array_pop($exploded) );
    $requestName = strtolower( implode('_', $exploded) );

    // File doesn't exist
    if ( !file_exists( $file = $requestType . '/' . $requestName . '/' . $requestName . '_' . $classType[0] . '.php' ) ) {

        file_put_contents(LOG_FILE, "[SYSTEM] File doesn't exists '$file' for '$className'\n", FILE_APPEND);
        exit();
    }

    // file exists: fetch it at last!
    require_once $file;

    // File loaded but class still doesn't exists ...
    if ( !class_exists ( $className ) ) {

        file_put_contents(LOG_FILE, "[SYSTEM] Class does not exist '$className' in '$file'\n", FILE_APPEND);
        exit();
    }
});

// Error handler
//--------------
set_error_handler( function ( $severity, $message, $filename, $lineno ) {

    if ( error_reporting() & $severity ) {

        file_put_contents(LOG_FILE, "[SYSTEM] Exception catched on line '$lineno' of file '$filename', message '$message'\n", FILE_APPEND);
        Throw new ErrorException( $message, 0, $severity, $filename, $lineno );
    }
});

// Launch controller
//------------------
$class = ucfirst( strtolower(Urlparser_Library_Controller::getRequestParam('request_name')) ) . '_'
       . ucfirst( strtolower(Urlparser_Library_Controller::getRequestParam('request_type')) ) . '_'
       . 'Controller';
$class::launch();
