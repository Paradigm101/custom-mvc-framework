<?php

/**
 * Unique entry point for the whole website
 */
// TBD: .htaccess
// TBD: manage security: SQL injection, script injection, crossplateform forgery, etc...

// Globals
//-------------
require_once 'config.php';
require_once 'global_functions.php';

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

        if ($severity & E_ERROR)                $error_type .= "ERROR|";
        if ($severity & E_WARNING)              $error_type .= "WARNING|";
        if ($severity & E_PARSE)                $error_type .= "PARSE|";
        if ($severity & E_NOTICE)               $error_type .= "NOTICE|";
        if ($severity & E_CORE_ERROR)           $error_type .= "CORE_ERROR|";
        if ($severity & E_CORE_WARNING)         $error_type .= "CORE_WARNING|";
        if ($severity & E_COMPILE_ERROR)        $error_type .= "COMPILE_ERROR|";
        if ($severity & E_COMPILE_WARNING)      $error_type .= "COMPILE_WARNING|";
        if ($severity & E_USER_ERROR)           $error_type .= "USER_ERROR|";
        if ($severity & E_USER_WARNING)         $error_type .= "USER_WARNING|";
        if ($severity & E_USER_NOTICE)          $error_type .= "USER_NOTICE|";
        if ($severity & E_STRICT)               $error_type .= "STRICT|";
        if ($severity & E_RECOVERABLE_ERROR)    $error_type .= "RECOVERABLE_ERROR|";
        if ($severity & E_DEPRECATED)           $error_type .= "DEPRECATED|";
        if ($severity & E_USER_DEPRECATED)      $error_type .= "USER_DEPRECATED|";

        // Unknown error
        if ( $error_type == "" ) {

            $error_type = "UNKNOWN";
        }
        // Formating error type: remove last character '|'
        else {
            
            $error_type = substr( $error_type, 0, -1);
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

// Start user session
//-------------------
Session_Library_Controller::initSession();

// Get request type (no default)
//------------------------------
$request_type      = strtolower(Urlparser_Library_Controller::getRequestParam('rt'));
$request_type_name = convertRequestTypeToName($request_type);

// Unknown or forbidden request type
if ( !(in_array($request_type, array(REQUEST_TYPE_PAGE, REQUEST_TYPE_AJAX, REQUEST_TYPE_API))) ) {

    // Error message
    if ( !$request_type_name ) {
        $error_message = "Unknown request type [$request_type]";
    }
    else {
        $error_message = "Forbidden request type [$request_type_name]";
    }

    // Launch error management
    Error_Library_Controller::launch($error_message, $request_type);

    // Quit
    exit();
}

// Get request name
//-----------------
$request_name = strtolower(Urlparser_Library_Controller::getRequestParam('rn'));

// Request name not found
if ( !$request_name ) {

    // Default page
    if ( $request_type == REQUEST_TYPE_PAGE ) {
        $request_name = DEFAULT_PAGE;
    }
    else {
        Error_Library_Controller::launch("No $request_type name set!", $request_type);
        exit();
    }
}

// File doesn't exist
if ( !( file_exists( $file = $request_type_name . '/' . $request_name . '/' . $request_name . '_c.php' ) ) ) {

    // Launch the user error page
    Error_Library_Controller::launch("The service you are trying to access doesn't exist", $request_type);

    // Trace the missing file for dev
    Log_Library_Controller::trace("File doesn't exist '$file' for request '$request_name' of type '$request_type_name'");

    // And leave
    exit();
}

// File exists, fetch it
require_once $file;

// Controller doesn't exist
if ( !( class_exists( $class = ucfirst($request_name) . '_' . ucfirst($request_type_name) . '_Controller' ) ) ) {

    // Launch the user error page
    Error_Library_Controller::launch("Something wrong happened, try again later", $request_type);

    // Trace the missing class
    Log_Library_Controller::trace("Controller '$class' doesn't exist in file '$file'");

    // And leave
    exit();
}

// Launch request creation
//------------------------
$class::launch();
