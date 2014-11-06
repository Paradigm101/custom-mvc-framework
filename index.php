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
// Adding E_STRICT for PHP version < 5.4
error_reporting( E_ALL | E_STRICT );

set_error_handler( function ( $severity, $message, $filename, $lineno, $context ) {

    // If error needs to be reported
    if ( error_reporting() & $severity ) {

        // Getting error type
        $error_type = "";

        if ($severity & E_ERROR)                $error_type = "ERROR";
        if ($severity & E_WARNING)              $error_type = "WARNING";
        if ($severity & E_PARSE)                $error_type = "PARSE";
        if ($severity & E_NOTICE)               $error_type = "NOTICE";
        if ($severity & E_CORE_ERROR)           $error_type = "CORE_ERROR";
        if ($severity & E_CORE_WARNING)         $error_type = "CORE_WARNING";
        if ($severity & E_COMPILE_ERROR)        $error_type = "COMPILE_ERROR";
        if ($severity & E_COMPILE_WARNING)      $error_type = "COMPILE_WARNING";
        if ($severity & E_USER_ERROR)           $error_type = "USER_ERROR";
        if ($severity & E_USER_WARNING)         $error_type = "USER_WARNING";
        if ($severity & E_USER_NOTICE)          $error_type = "USER_NOTICE";
        if ($severity & E_STRICT)               $error_type = "STRICT";
        if ($severity & E_RECOVERABLE_ERROR)    $error_type = "RECOVERABLE_ERROR";
        if ($severity & E_DEPRECATED)           $error_type = "DEPRECATED";
        if ($severity & E_USER_DEPRECATED)      $error_type = "USER_DEPRECATED";

        // Unknown error
        if ( $error_type == "" ) {

            $error_type = "UNKNOWN";
        }

        // Add date
        $comps = explode(' ', microtime());
        $micro = sprintf('%06d', $comps[0] * 1000000);
        $date = date('H:i:s') . ":$micro";

        // Store error
        file_put_contents(LOG_FILE, "[$date] [$error_type] '$message' in $filename (L.$lineno)\n", FILE_APPEND);
    }

    // Don't execute PHP internal error manager: show must go on!
    return;
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
    
    // Launch the user error page
    Error_Library_Controller::launch("Something wrong happened, try again later", $type);
    
    // And leave
    exit();
}

// File exists, fetch it
require $file;

// Controller doesn't exist
if ( !( class_exists( $class = ucfirst($name) . '_' . ucfirst($type) . '_Controller' ) ) ) {

    // Trace the missing class
    Log_Library_Controller::trace("Controller '$class' doesn't exist in file '$file'");

    // Launch the user error page
    Error_Library_Controller::launch("Something wrong happened, try again later", $type);

    // And leave
    exit();
}

// Start user session
//-------------------
session_start();

// Doing the work!
//----------------
$class::launch();
