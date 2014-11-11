<?php

/**
 * Managing user session
 */
abstract class Session_Library_Controller {

    // Model
    static protected $model;

    // user ID
    static protected $id_user;

    // Get user id for this session (if exists)
    static public function getCurrentUserId() {

        return static::$id_user;
    }

    // Check if user is logged in
    static public function isUserLoggedIn() {

        return ( static::$id_user ? true : false );
    }

    // Starting the session, should be done before the page creation (like in the index...)
    static public function initSession()
    {
        // Start PHP session
        session_start();

        // Set session model
        static::$model = new Session_Library_Model();

        // Set user id if any
        static::$id_user = static::$model->getUserForSession( session_id() );

        // 
        Menu_Library_Controller::getPageMenu();
    }
}
