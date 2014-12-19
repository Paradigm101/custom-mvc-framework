<?php

/**
 * Manage session access to DB
 */
class Session_LIB_Model extends Base_LIB_Model {

    // Retrieve current active session for user
    public function getSessionForUser( $idUser = null, $is_active = true ) {

        // No user => assume current PHP session
        if ( !$idUser ) {

            return session_id();
        }

        // Sanitize data and add quotes
        $idUser    = $this->getQuotedValue($idUser);
        $is_active = $this->getQuotedValue( ( $is_active ? 1 : 0 ) );

        // Retrieve user for this session
        $this->query( "SELECT id_session FROM sessions WHERE id_user = $idUser AND is_active = $is_active;");

        // Get user data
        $session = $this->fetchNext();

        // Return session id if found
        if ( $session ) {

            return $session->id_session;
        }

        // No session found
        return null;
    }
    
    public function getUserForSession( $id_session ) {

        // Sanitize data and add quotes
        $id_session = $this->getQuotedValue($id_session);

        // Retrieve user for this session if it's active
        $this->query( "SELECT id_user FROM sessions WHERE id_session = $id_session AND is_active = 1;");

        // Get user data
        $user = $this->fetchNext();

        // Return user id if found
        if ( $user ) {

            return 0 + $user->id_user;
        }

        // No user found
        return null;
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

    // Start user session:
    //      close every current active session
    //      then create a new session
    public function startUserSession( $idUser, $idSession ) {

        // First close user session (if any)
        $this->closeUserSession( $idUser );
        
        // Sanitize data and add quotes
        $idUser    = $this->getQuotedValue($idUser);
        $idSession = $this->getQuotedValue($idSession);

        // create a new session in DB
        $this->query( "INSERT INTO sessions (id_session, id_user) VALUES ($idSession, $idUser)");
    }

    // Close user session: set active sesion to inactive
    public function closeUserSession( $idUser = null ) {

        // No user id, nothing can be done
        if ( !$idUser ) {
            return;
        }

        // Sanitize data and add quotes
        $idUser = $this->getQuotedValue($idUser);

        // Remove temporary session table (board, ...)
        //--------------------------------------------
        // Retrieve user active session id
        $this->query( "SELECT id_session FROM sessions WHERE id_user = $idUser AND is_active = 1;" );

        // No session found, job is done
        if ( !($result = $this->fetchNext() ) )
        {
            return;
        }
        
        // Get all tables
        $this->query( 'SELECT * FROM information_schema.tables' );

        while( $table = $this->fetchNext() )
        {
            // Check name
            $tableName = $table->TABLE_NAME;
            
            $explode = explode('_', $table->TABLE_NAME);
            $first   = strtolower( $explode[0] );
            $last    = array_pop( $explode );

            // If table is temporary and session has to be deleted
            if ( ( $first == 'tmp' ) && ( $last == $result->id_session ) ) {

                $this->query( "DROP TABLE $tableName" );
            }
        }

        // Set inactive user active session
        //---------------------------------
        // TBD: is it faster to not check is_active = 1??
        $this->query( "UPDATE sessions SET is_active = 0 WHERE id_user = $idUser AND is_active = 1;" );
    }
}
