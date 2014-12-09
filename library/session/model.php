<?php

/**
 * Manage session access to DB
 */
class Session_LIB_Model extends Base_LIB_Model {

    // TBD: manage multiple session for user
    public function getSessionIdByUserId( $idUser = null ) {

        // No user => assum current session
        if ( !$idUser ) {

            return session_id();
        }
        
        // Sanitize data and add quotes
        $idUser = $this->getQuotedValue($idUser);

        // Retrieve user for this session
        $this->query( "SELECT id_session FROM sessions WHERE id_user = $idUser");

        // Get user data
        $session = $this->fetchNext();

        // Return user id if found
        if ( $session ) {
            return $session->id_session;
        }

        // else return null
        return null;
    }
    
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
    
    // For log-in
    // TBD: return error status
    public function startUserSession( $idUser, $idSession ) {

        // Sanitize data and add quotes
        $idUser    = $this->getQuotedValue($idUser);
        $idSession = $this->getQuotedValue($idSession);

        // Retrieve previous user session
        $this->query( "SELECT * FROM sessions WHERE id_user = $idUser");

        // if there is an old session stored
        if ( $this->fetchNext() ) {
        
            // Update session in DB
            $this->query( "UPDATE sessions SET id_session = $idSession WHERE id_user = $idUser");
        }
        // no old session
        else {

            // create a new session in DB
            $this->query( "INSERT INTO sessions (id_session, id_user) VALUES ($idSession, $idUser)");
        }
        
        return true;
    }

    // Log-out
    public function closeUserSession( $idUser, $idSession = null ) {

        // Sanitize data and add quotes
        $idUser = $this->getQuotedValue($idUser);

        // For a given session: remove this specific session and relative temporary table
        if ( $idSession ) {

            // Prepare temporary table deletion
            $sessions = array( $idSession );
            
            // Sanitize data
            $idSession = $this->getQuotedValue($idSession);

            // Remove specific session
            $sessionCondition = " id_session = $idSession ";
        }
        else {

            // Retrieve user open sessions
            $this->query( "SELECT * FROM sessions WHERE id_user = $idUser" );
            
            // Store session IDs for temporary table deletion
            $sessions = array();
            while( $row = $this->fetchNext() ) {
                $sessions[] = $row->id_session;
            }

            // Remove all user sessions
            $sessionCondition = ' 1 = 1 ';
        }

        // Retrieve previous user session
        $this->query( "DELETE FROM sessions WHERE id_user = $idUser AND $sessionCondition " );

        // Remove temporary session table (board, ...)
        //--------------------------------------------
        
        // Get all tables
        $this->query( 'SELECT * FROM information_schema.tables' );

        // Check name
        while( $table = $this->fetchNext() ) {

            $tableName = $table->TABLE_NAME;
            
            $explode = explode('_', $table->TABLE_NAME);
            $first   = strtolower( $explode[0] );
            $last    = array_pop( $explode );

            // If table is temporary and session has to be deleted
            if ( ( $first == 'tmp' ) && ( in_array( $last, $sessions ) ) ) {

                $this->query( "DROP TABLE $tableName" );
            }
        }

        // TBD: manage error
        return true;
    }
}
