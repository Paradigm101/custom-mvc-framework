<?php

/**
 * Unique entry point for the whole website
 */
// TBD: .htaccess
// TBD: manage security: SQL injection, script injection, crossplateform forgery, etc...

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

// Default request type: page
if ( !($type = strtolower(Urlparser_Library_Controller::getRequestParam('request_type'))) ) {

    $type = 'page';
}

// Unknown type
if ( !(in_array($type, array('page', 'ajax', 'api'))) ) {

    Log_Library_Controller::trace("Unknown request type '$type'");
    exit();
}

// Get request name
$name = strtolower(Urlparser_Library_Controller::getRequestParam('request_name'));

// Request name not found
if ( !$name ) {

    // Default page
    if ( $type == 'page' ) {
        $name = 'main';
    }
    else {
        Error_Library_Controller::launch("No $type name set!", $type);
        exit();
    }
}

// File doesn't exist
if ( !( file_exists( $file = $type . '/' . $name . '/' . $name . '_c.php' ) ) ) {

    // Trace the missing file
    Log_Library_Controller::trace("File doesn't exist '$file' for request '$name' of type '$type'");
    
    // Launch the error
    Error_Library_Controller::launch("No $type name set!", $type);
    
    // And leave
    exit();
}

// File exists, fetch it
require $file;

// Controller doesn't exist
if ( !( class_exists( $class = ucfirst($name) . '_' . ucfirst($type) . '_Controller' ) ) ) {

    // Trace the missing class
    Log_Library_Controller::trace("Controller '$class' doesn't exist in file '$file'");
    
    // Launch the error
    Error_Library_Controller::launch("Something wrong happened, try again later", $type);
    
    // And leave
    exit();
}

// Doing the work!
//----------------
$class::launch();
