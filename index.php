<?php
/**
 * Unique entry point for the whole website
 */
// Globals
//-------------
require 'config.php';

// Autoload
//---------
spl_autoload_register( function ( $className ) {

    // Parse out filename where class should be located
    list($fileName, $classType) = explode('_', $className);

    // Compose file name based on class type
    switch ( strtolower( $classType ) ) {
        case 'controller' :
            $file = 'controllers/' . strtolower( $fileName ) . '_c.php';
            break;
        
        case 'model' :
            $file = 'models/' . strtolower( $fileName ) . '_m.php';
            break;
        
        case 'view' :
            $file = 'views/' . strtolower( $fileName ) . '_v.php';
            break;
        
        case 'ajax' :
            $file = 'ajax/' . strtolower( $fileName ) . '_a.php';
            break;
        
        case 'library' :
            $file = 'libraries/' . strtolower( $fileName ) . '.php';
            break;
        
        default:
            Error_Library::launch("Unknown class type ($classType) for $className");
            exit();
    }

    // File doesn't exist
    if ( !file_exists( $file ) )
    {
        Error_Library::launch("Can't find file '$file' containing class '$className'");
        exit();
    }

    // file exists: fetch it
    require_once $file;

    // Unknow class
    if ( !class_exists ( $className ) )
    {
        Error_Library::launch("Class does not exist : $className in $file");
        exit();
    }
});

// Error handler
//--------------
set_error_handler( function ( $severity, $message, $filename, $lineno ) {

    if ( error_reporting() & $severity ) {

        file_put_contents(LOG_FILE, "[SYSTEM] Exception catched on line [$lineno] of file [$filename] : $message\n", FILE_APPEND);
        Throw new ErrorException( $message, 0, $severity, $filename, $lineno );
    }
});

// Retrieve page
$page = Urlparser_Library::getRequestParam('page');

// Manage Ajax first
//------------------
if ( $page == 'ajax' ) {

    // Retrieve action
    $action = Urlparser_Library::getRequestParam('action');
    
    // Wrong ajax action
    if (!$action) {

        Log_Library::trace('Ajax requested with no action specified');
        exit();
    }

    // Ajax action not recognize
    if ( !file_exists( "ajax/" . $action . '_a.php' ) ) {

        Log_Library::trace("Unknown Ajax action requested (file doesn't exists) : [$action]");
        exit();
    }

    // Launch AJAX
    $class = ucfirst( $action ) . '_Ajax';
    $class::launch();

    // And leave
    exit();
}

// Manage 'page not found'
//------------------------
if ( !$page || !file_exists( "controllers/" . $page . '_c.php' ) ) {

    switch (PAGE_NOT_FOUND) {

        case 'display':
            Error_Library::launch('Page not found');
            exit();

        // TBD: manage a redirection with POST data?
        case 'redirect':
        default:
            header('Location: ' . SITE_ROOT . 'page=main' );
    }
}

// Display page: Get controller class and launch it
$class = ucfirst( $page ) . '_Controller';
$class::launch();

// END
//----