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

        // Set starting time of page processing now!
        Page_Manager_LIB::setStartingTime();

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
    static public function hasAccess( $requestName, $requestTypeCode = REQUEST_TYPE_PAGE ) {

        // Getting class Name
        $className = $requestName . '_' . convertRequestCodeToClass( $requestTypeCode ) . '_C';

        // Get xml security file
        $securityFile = str_replace( '_c.php', '.xml', getFileForClass($className) );

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
