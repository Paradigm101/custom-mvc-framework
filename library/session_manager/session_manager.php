<?php

/**
 * Managing user session
 */
abstract class Session_Manager_LIB {

    // Model
    static private $model;

    // user ID
    static private $idUser;

    // Starting the session, should be done before the page creation (like in the index...)
    static public function initSession() {

        // Start PHP session
        session_start();

        // Set session model
        self::$model = new Session_Manager_LIB_Model();

        // Set user id if any
        self::$idUser = static::$model->getUserForSession( session_id() );
    }

    // Get user id for this session (if exists)
    static public function getUserId() {

        return static::$idUser;
    }

    // Check if user is logged in
    static public function isUserLoggedIn() {

        return ( static::$idUser ? true : false );
    }

    // To send javascript to client
    static private $classWithJavascript = array( 'Url_Manager_LIB' );

    // Page list (should be const but seems impossible to be private and const for whatever reason...)
    static private $pages = array( array( 'fileName' => 'main',          'shortcut' => 'h', 'withCtrl' => true, 'headerTitle' => '<strong>H</strong>ome',       'description' => 'Home page : menu' ),
                                   array( 'fileName' => 'bootstrapdemo', 'shortcut' => 'b', 'withCtrl' => true, 'headerTitle' => '<strong>B</strong>ootstrap',  'description' => 'Bootstrap demonstration page' ),
                                   array( 'fileName' => 'table',         'shortcut' => 't', 'withCtrl' => true, 'headerTitle' => '<strong>T</strong>able',      'description' => 'Table demonstration page' ),
                                   array( 'fileName' => 'api',           'shortcut' => 'p', 'withCtrl' => true, 'headerTitle' => 'A<strong>P</strong>I',        'description' => 'API access' ),
                                   array( 'fileName' => 'about',         'shortcut' => 'a', 'withCtrl' => true, 'headerTitle' => '<strong>A</strong>bout',      'description' => 'About page : my resume' ) );

    // Get page list for user
    static public function getUserPages() {

        // Initialize accessible pages
        $pages = array();

        // For every existing page (safeClone to avoid any changes)
        foreach( Tools_LIB::safeClone(self::$pages) as $page ) {

            // check access
            if ( static::hasAccess($page['fileName'] . '_PAG_C') ) {

                // Add page to the accessible pages
                $pages[] = $page;
            }
        }

        // Send pages
        return $pages;
    }

    // Send javascript to client
    static public function getJavascript() {

        $script = "";

        // Constantes
        //-----------
        $script .= "// Constantes\n"
                . "//-----------\n"
                . "REQUEST_TYPE_AJAX = " . REQUEST_TYPE_AJAX . ";\n"
                . "REQUEST_TYPE_PAGE = " . REQUEST_TYPE_PAGE . ";\n"
                . "\n";

        // Retrieve javascript from each subscribed class
        foreach ( static::$classWithJavascript as $class ) {
            $script .= $class::getJavascript() . "\n\n";
        }

        // Add shortcuts
        //--------------
        $script .= "// Shortcuts\n"
                . "//----------\n"
                . "$(function () {\n"
                .  "    $(document).keypress(function(e) {\n";

        foreach ( static::getUserPages() as $page ) {

            $shortcutCondition = 'e.which == ' . ord( $page['shortcut'] ) . ( $page['withCtrl'] ? ' && e.ctrlKey' : '' );

            $script .= "        if ( $shortcutCondition ) {\n"
                    . "             e.preventDefault();\n"
                    . "             $(location).attr('href', '" . Url_Manager_LIB::getUrlForRequest( $page['fileName'] ) . "' );\n"
                    . "         }\n";
        }

        $script .= "    });\n"
                . "});\n\n";

        return $script;
    }

    // Getting user's IP address
    static public function getUserIP() {

        return getenv('HTTP_CLIENT_IP')?:
               getenv('HTTP_X_FORWARDED_FOR')?:
               getenv('HTTP_X_FORWARDED')?:
               getenv('HTTP_FORWARDED_FOR')?:
               getenv('HTTP_FORWARDED')?:
               getenv('REMOTE_ADDR');
    }

    // Security check
    static public function hasAccess( $className ) {

        // Get XML security file
        $securityFile = getFileForClass($className, true /* security file */ );

        // File doesn't exist, allow everyone
        if ( !is_file( $securityFile )) {

            return true;
        }

        // Parsing security file
        $securityXML = simplexml_load_file( $securityFile );

        // Check role
        if ( !empty( $securityXML->role ) ) {

            // Retrieving user role
            $userRole = static::$model->getUserRole( static::$idUser );

            // By deny
            if ( !empty( $securityXML->role->deny ) ) {

                // User is persona non grata
                if ( in_array( $userRole, (array) $securityXML->role->deny ) ) {

                    return false;
                }

                // User has access
                return true;
            }
            // By allowance
            else {

                // User is persona non grata
                if ( !in_array( $userRole, (array) $securityXML->role->allow ) ) {

                    return false;
                }

                // User has access
                return true;
            }
        }

        // User has access
        return true;
    }
}
