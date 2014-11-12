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
//      className = <Module>_<RequestType>_<Complement>
//      Modules can have underscore(s)
//      Request Type : PAG, AJA, API, LIB, TAB
//      Complement: if request type is PAG, AJA, API then _M, _V, _C
//           else can be empty or anything except a request type (PAG, AJA, API, LIB, TAB)
//---------
spl_autoload_register( function ( $className ) {

    // Parse out class name
    $exploded = explode('_', $className);

    // Retrieve last element
    $last = strtolower( array_pop($exploded) );
    
    // If last element is a request type, no complement
    if ( in_array($last, array('pag', 'aja', 'api', 'lib', 'tab'))) {

        $complement = '';
        $requestType = $last;
    }
    // Last element is the complement and next retrive request type
    else {
        $complement  = $last;
        $requestType = strtolower( array_pop($exploded) );
    }

    // Retrieve module name (can have underscores)
    $module = strtolower( implode('_', $exploded) );

    // Convert request type into folder name
    switch ( $requestType ) {
        case 'pag': $requestTypeName = 'page';       break;
        case 'aja': $requestTypeName = 'ajax';       break;
        case 'api': $requestTypeName = 'api';        break;
        case 'lib': $requestTypeName = 'library';    break;
        case 'tab': $requestTypeName = 'table';      break;
        default:
            file_put_contents(LOG_FILE, "[AUTOLOAD] Unknown request type [$requestType] for [$className]\n", FILE_APPEND);
            exit();
    }

    // Complement
    switch ( $requestCode = convertRequestTypeToCode( $requestTypeName ) ) {
        
        // Case MVC
        case REQUEST_TYPE_PAGE:
        case REQUEST_TYPE_AJAX:
        case REQUEST_TYPE_API:
            $fileName = $module . '_' . $complement;
            break;

        // Other case, default file name is module else name is complement
        case REQUEST_TYPE_LIBRARY:
        case REQUEST_TYPE_TABLE:
            if ( $complement ) {
                $fileName = $complement;
            }
            else {
                $fileName = $module;
            }
            break;
        default:
            file_put_contents(LOG_FILE, "[AUTOLOAD] Unknown request code [$requestCode] for request [$requestTypeName] in [$className]\n", FILE_APPEND);
            exit();
    }

    // File
    $file = $requestTypeName . '/' . $module . '/' . $fileName . '.php';

    // File doesn't exist
    if ( !file_exists( $file ) ) {

        file_put_contents(LOG_FILE, "[AUTOLOAD] File doesn't exists [$file] for [$className]\n", FILE_APPEND);
        exit();
    }

    // file exists: fetch it at last!
    require_once $file;

    // File loaded but class still doesn't exists ...
    if ( !class_exists ( $className ) ) {

        file_put_contents(LOG_FILE, "[AUTOLOAD] Class does not exist [$className] in [$file]\n", FILE_APPEND);
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

        // Store error
        file_put_contents(LOG_FILE, "[ERROR_HANDLER] Error type [$error_type] error message [$message] in file [$filename] (L.$lineno)\n", FILE_APPEND);
    }

    // Don't execute PHP internal error manager: show must go on!
    return;
});

// Start user session
//-------------------
Session_LIB::initSession();

// Get request type (no default)
//------------------------------
$request_type = strtolower(Url_Parser_LIB::getRequestParam('rt'));

// Assume that no request type means root of website
if ( $request_type == null ) {
    $request_type = REQUEST_TYPE_PAGE;
}

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
    Error_LIB::launch($error_message, $request_type);

    // Quit
    exit();
}

// Get request name
//-----------------
$request_name = strtolower(Url_Parser_LIB::getRequestParam('rn'));

// Request name not found
if ( !$request_name ) {

    // Default page
    if ( $request_type == REQUEST_TYPE_PAGE ) {
        $request_name = DEFAULT_PAGE;
    }
    else {
        Error_LIB::launch("No $request_type name set!", $request_type);
        exit();
    }
}

// File doesn't exist
if ( !( file_exists( $file = $request_type_name . '/' . $request_name . '/' . $request_name . '_c.php' ) ) ) {

    // Launch the user error page
    Error_LIB::launch("The service you are trying to access doesn't exist", $request_type);

    // Trace the missing file for dev
    Log_LIB::trace("[INDEX] File doesn't exist '$file' for request '$request_name' of type '$request_type_name'");

    // And leave
    exit();
}

// File exists, fetch it
require_once $file;

// Controller doesn't exist
if ( !( class_exists( $class = ucfirst($request_name) . '_' . strtolower( substr( $request_type_name, 0, 3 ) ) . '_C' ) ) ) {

    // Launch the user error page
    Error_LIB::launch("Something wrong happened, try again later", $request_type);

    // Trace the missing class
    Log_LIB::trace("[INDEX] Controller '$class' doesn't exist in file '$file'");

    // And leave
    exit();
}

// Launch request creation
//------------------------
$class::launch();
