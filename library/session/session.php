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
    static public function initSession()
    {

        // Set starting time of page processing now!
        Page_LIB::setStartingTime();

        // Start PHP session
        session_start();

        // Set session model
        self::$model = new Session_LIB_Model();

        // Set user id if any
        self::$idUser = static::$model->getUserForSession( session_id() );
    }

    // Start session for user (log-in, ...)
    static public function startUserSession( $idUser )
    {
        self::$model->startUserSession( $idUser, session_id() );
    }

    // Close user session (for log-out, ...)
    static public function closeUserSession( $idUser = null )
    {
        // No user => assume current user
        if ( !$idUser ) {

            $idUser = self::getUserId();
        }

        self::$model->closeUserSession( $idUser );
    }

    // Get user id for this session (if exists)
    static public function getUserId()
    {
        return static::$idUser;
    }

    // Return current active session id for any user
    static public function getSession( $idUser = null, $is_active = true )
    {
        // No user => assume current user
        if ( !$idUser ) {

            $idUser = static::getUserId();
        }

        $sessionId = self::$model->getSessionForUser( $idUser, $is_active );

        // No session for user, assume current session
        if ( !$sessionId ) {
            
            return session_id();
        }
        
        // Finaly send session
        return $sessionId;
    }

    // Check if user is logged in
    static public function isUserLoggedIn()
    {
        return ( static::$idUser ? true : false );
    }

    // Get user role
    static public function getUserRole()
    {
        return static::$model->getUserRole( static::$idUser );
    }
    
    // Getting user's IP address
    static public function getUserIP()
    {
        return getenv('HTTP_CLIENT_IP')?:
               getenv('HTTP_X_FORWARDED_FOR')?:
               getenv('HTTP_X_FORWARDED')?:
               getenv('HTTP_FORWARDED_FOR')?:
               getenv('HTTP_FORWARDED')?:
               getenv('REMOTE_ADDR');
    }

    // Security check
    static public function hasAccess( $requestName, $requestTypeCode = REQUEST_TYPE_PAGE )
    {
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
