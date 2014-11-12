<?php

class Login_AJA_M extends Base_AJA_M {

    // Check if user and password are correct
    // Return user id if yes, null otherwise
    public function checkPassword( $email, $password ) {

        // Sanitize data and add quotes
        $email    = $this->getQuotedValue($email);
        $password = $this->getQuotedValue($password);

        // Retrieve users with the same email
        $this->query( "SELECT * FROM users WHERE email = $email AND password = $password");

        $user_data = $this->fetchNext();

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
        $id_user    = $this->getQuotedValue($id_user);
        $id_session = $this->getQuotedValue($id_session);

        // Retrieve previous user session
        $this->query( "SELECT * FROM sessions WHERE id_user = $id_user");

        // if there is an old session stored
        if ( $this->fetchNext() ) {

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
