<?php

/**
 * Session page: to test how session works and create login/logout system
 */
abstract class Session_Page_Controller extends Base_Page_Controller {

    // Main process: assign data to view
    static protected function process() {

        static::assign('session_id', session_id());
        static::assign('IsLoggedIn', Session_Library_Controller::isUserLoggedIn() ? 'YES' : 'NO' );
    }
}
