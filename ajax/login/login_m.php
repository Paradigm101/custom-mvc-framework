<?php

class Login_Ajax_Model extends Base_Ajax_Model {

    // Check if user and password are correct
    // Return user id if yes, null otherwise
    public function checkPassword( $email, $password ) {

        // Sanitize data and add quotes
        $email    = $this->db->getQuotedValue($email);
        $password = $this->db->getQuotedValue($password);

        // Retrieve users with the same email
        $this->query( "SELECT * FROM users WHERE email = $email AND password = $password");

        $user_data = $this->db->fetchNext();

        // User/password exist
        if ( $user_data ) {
            return $user_data->id;
        }

        // No match user/password
        return NULL;
    }

    // Store session
    public function storeSession( $id_user, $id_session ) {

        // Sanitize data and add quotes
        $id_user    = $this->db->getQuotedValue($id_user);
        $id_session = $this->db->getQuotedValue($id_session);

        // Retrieve previous user session
        $this->query( "SELECT * FROM sessions WHERE id_user = $id_user");

        // if there is an old session stored
        if ( $this->db->fetchNext() ) {

            // Update session in DB
            $this->query( "UPDATE sessions SET id_session = $id_session WHERE id_user = $id_user");
        }
        // no old session
        else {
            // create a new session in DB
            $this->query( "INSERT INTO sessions (id_session, id_user) VALUES ($id_session, $id_user)");
        }
    }
}
