<?php

/**
 * Managing user session
 */
abstract class Session_Manager_LIB {

    // To send javascript to client
    static private $classWithJavascript = array( 'Url_Manager_LIB' );

    // Page list (should be const but seems impossible to be private and const for whatever reason...)
    // TBD manage menu according to rights/roles and add API/AJAX
    static private $requests = array( array( 'shortcut' => 'h', 'withControl' => true, 'fileName' => 'main',          'headerTitle' => '<strong>H</strong>ome' ),
                                      array( 'shortcut' => 'b', 'withControl' => true, 'fileName' => 'bootstrapdemo', 'headerTitle' => '<strong>B</strong>ootstrap' ),
                                      array( 'shortcut' => 'e', 'withControl' => true, 'fileName' => 'session',       'headerTitle' => 'S<strong>e</strong>ssion' ),
                                      array( 'shortcut' => 't', 'withControl' => true, 'fileName' => 'table',         'headerTitle' => '<strong>T</strong>able' ),
                                      array( 'shortcut' => 'p', 'withControl' => true, 'fileName' => 'api',           'headerTitle' => 'A<strong>P</strong>I' ),
                                      array( 'shortcut' => 'a', 'withControl' => true, 'fileName' => 'about',         'headerTitle' => '<strong>A</strong>bout' ) );

    // Define menu pages
    // TBD manage menu according to rights/roles and return only pages
    static public function getPageMenu() {

        // Avoid those pages of being changed
        return Tools_LIB::safeClone(self::$requests);
    }

    // Send javascript to client
    static public function getJavascript() {

        $script = "";

        // Retrieve javascript from each subscribed class
        foreach ( static::$classWithJavascript as $class ) {
            $script .= $class::getJavascript();
        }

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

    // Model
    static private $model;

    // user ID
    static private $id_user;

    // Starting the session, should be done before the page creation (like in the index...)
    static public function initSession()
    {
        // Start PHP session
        session_start();

        // Set session model
        self::$model = new Session_Manager_LIB_Model();

        // Set user id if any
        self::$id_user = static::$model->getUserForSession( session_id() );
    }

    // Get user id for this session (if exists)
    static public function getUserId() {

        return static::$id_user;
    }

    // Check if user is logged in
    static public function isUserLoggedIn() {

        return ( static::$id_user ? true : false );
    }

    // Security check
    static public function hasAccess( $className ) {

        $securityFile = getFileForClass($className, true /* security file */ );

//        $test = simplexml_load_file( $securityFile );
//        
//        Log_LIB::trace($test);

        return true;
    }
}
