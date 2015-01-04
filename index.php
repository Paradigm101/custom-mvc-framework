<?php

/**
 * Unique entry point for the whole website
 */
// TBD: manage security: SQL injection, script injection, crossplateform forgery, etc...

date_default_timezone_set('America/Toronto');

// Globals
//-------------
require_once 'config.php';
require_once 'global_functions.php';

// Autoload
//      className = <Module>_<RequestType>_<Complement>
//      Modules can have underscore(s)
//      Request Type : PAG, AJA, API, LIB, TAB
//      Complement: if request type is PAG, AJA, API then _M, _V, _C
//           else can be empty or anything except a request type (PAG, AJA, API, LIB, TAB)
//---------
spl_autoload_register( $myAutoload = function ( $className ) {

    // File
    $file = getFileForClass($className);

    // File doesn't exist
    if ( !file_exists( $file ) ) {

        $comps = explode(' ', microtime());
        $micro = sprintf('%06d', $comps[0] * 1000000);
        file_put_contents(LOG_FILE, '[' . date('H:i:s') . ":$micro] [AUTOLOAD] File doesn't exists [$file] for [$className]\n", FILE_APPEND);
        return 'No service';
    }

    // file exists: fetch it at last!
    require $file;

    // File loaded but class still doesn't exists ...
    if ( !class_exists ( $className ) ) {

        $comps = explode(' ', microtime());
        $micro = sprintf('%06d', $comps[0] * 1000000);
        file_put_contents(LOG_FILE, '[' . date('H:i:s') . ":$micro] [AUTOLOAD] Class does not exist [$className] in [$file]\n", FILE_APPEND);
        return 'Internal error';
    }

    return '';
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

        $comps = explode(' ', microtime());
        $micro = sprintf('%06d', $comps[0] * 1000000);
        file_put_contents(LOG_FILE, '[' . date('H:i:s') . ":$micro] [ERROR_HANDLER] Error type [$error_type] error message [$message] in file [$filename] (L.$lineno)\n", FILE_APPEND);
    }

    // Don't execute PHP internal error manager: show must go on!
    return;
});

// Start user session
//-------------------
Session_LIB::initSession();

// Get request type (no default)
//------------------------------
$requestTypeCode = strtolower(Url_LIB::getRequestParam('rt'));

// Wrong request type: log hacker/issue
if ( !(in_array($requestTypeCode, array(null, REQUEST_TYPE_AJAX, REQUEST_TYPE_API))) ) {

    // Log error
    $comps = explode(' ', microtime());
    $micro = sprintf('%06d', $comps[0] * 1000000);
    Log_LIB::trace('[' . date('H:i:s') . ":$micro] [INDEX] Wrong request type [$requestTypeCode] from [" . Session_LIB::getUserIP() . "]");
}

// Wrong or no request type means page
if ( !(in_array($requestTypeCode, array(REQUEST_TYPE_AJAX, REQUEST_TYPE_API))) ) {

    $requestTypeCode = REQUEST_TYPE_PAGE;
}

// Get request name
//-----------------
$requestName = strtolower(Url_LIB::getRequestParam('rn'));

// Request name not found (base is specific protected keyword)
if ( !$requestName || $requestName == 'base' ) {

    // Default page
    if ( $requestTypeCode == REQUEST_TYPE_PAGE ) {
        $requestName = DEFAULT_PAGE;
    }
    // Error for API or Ajax
    else {
        Error_LIB::process("Wrong service [$requestName]!", $requestTypeCode);
        exit();
    }
}

// Manage security
//----------------
if ( !Session_LIB::hasAccess( $requestName, $requestTypeCode ) ) {

    // Launch the user error page
    Error_LIB::process("No access", $requestTypeCode);

    // Trace the missing class (or not)
//    Log_LIB::trace("[INDEX] User try to access forbidden service [$requestName] of type [$requestTypeCode] IP [" . Session_LIB::getUserIP() . "]");

    // And leave
    exit();
}

// Something went wrong
//---------------------
if ( $myAutoload( $className = ucfirst( $requestName ) . '_' . convertRequestCodeToClass($requestTypeCode) . '_C' ) ) {

    // Launch the user error answer: page/ajax/api
    Error_LIB::process("No service", $requestTypeCode);

    // TBD: Log? There's gonna be a lot! (maybe customize according to environment...
    Log_LIB::trace("[INDEX] Wrong service [$requestName] of type [$requestTypeCode] from IP [" . Session_LIB::getUserIP() . ']');

    // And leave
    exit();
}

// Start Request
//--------------
$className::launch();
