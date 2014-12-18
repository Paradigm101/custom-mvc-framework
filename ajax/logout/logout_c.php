<?php

// Manage user log-out
abstract class Logout_AJA_C extends Base_AJA_C {

    static protected function process() {

        // Close current user session
        Session_LIB::closeUserSession();
    }
}
