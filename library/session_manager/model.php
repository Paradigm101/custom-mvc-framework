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
            return 0 + $user->id_user;
        }

        // else return null
        return NULL;
    }

    public function getUserRole( $idUser ) {

        // Guest
        if ( !$idUser ) {

            return 'guest';
        }

        // Sanitize data and add quotes
        $idUser = $this->getQuotedValue($idUser);

        $query = "SELECT "
                . "     r.name "
                . "FROM "
                . "     roles r "
                . "     INNER JOIN users u ON "
                . "         u.id_role = r.id "
                . "     AND u.id = $idUser ";

        // Execute query
        $this->query( $query );

        // Get data
        $role = $this->fetchNext();

        // Return data
        return $role->name;
    }
}
