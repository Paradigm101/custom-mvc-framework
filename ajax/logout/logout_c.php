<?php

// Manage user log-out
abstract class Logout_AJA_C extends Base_AJA_C {

    static protected function process() {

        // Close session for current user and current session
        // TBD: manage result
        $result = Session_LIB::closeUserSession( Session_LIB::getUserId(), Session_LIB::getSessionId() );
    }
}
