<?php

/**
 * Session page: to test how session works and create login/logout system
 */
abstract class Session_PAG_C extends Base_PAG_C {

    // Main process: assign data to view
    static protected function process() {

        static::assign('session_id', session_id());
        static::assign('IsLoggedIn', Session_Manager_LIB::isUserLoggedIn() ? 'YES' : 'NO' );
    }
}
