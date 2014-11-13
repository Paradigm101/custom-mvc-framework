<?php

/**
 * Manage session access to DB
 */
class Session_Manager_LIB_Model extends Model_Base_LIB {

    public function getUserForSession( $id_session ) {

        // Sanitize data and add quotes
        $id_session = $this->getQuotedValue($id_session);

        // Retrieve user for this session
        $this->query( "SELECT id_user FROM sessions WHERE id_session = $id_session");

        // Get user data
        $user = $this->fetchNext();

        // Return user id if found
        if ( $user ) {
            return $user->id_user;
        }

        // else return null
        return NULL;
    }
}
