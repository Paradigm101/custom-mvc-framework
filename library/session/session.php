<?php

/**
 * Managing user session
 */
abstract class Session_LIB {

    // Model
    static private $model;

    // user ID
    static private $idUser;

    // Starting the session, should be done before the page creation (like in the index...)
    static public function initSession() {

        // Set starting time of page processing now!
        Page_LIB::setStartingTime();

        // Start PHP session
        session_start();

        // Set session model
        self::$model = new Session_LIB_Model();

        // Set user id if any
        self::$idUser = static::$model->getUserForSession( session_id() );
    }

    // For log-in
    static public function startUserSession( $idUser, $idSession ) {

        return self::$model->startUserSession( $idUser, $idSession );
    }

    // For log-out
    static public function closeUserSession( $idUser = null, $idSession = null ) {

        // No user => assume current user
        if ( !$idUser ) {

            $idUser = static::getUserId();
        }

        return self::$model->closeUserSession( $idUser, $idSession );
    }

    // Get user id for this session (if exists)
    static public function getUserId() {

        return static::$idUser;
    }

    // TBD: return a session id for any user
    static public function getSessionId( $idUser = null ) {

        // No user => assume current user
        if ( !$idUser ) {

            $idUser = static::getUserId();
        }

        return self::$model->getSessionIdByUserId( $idUser );
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
