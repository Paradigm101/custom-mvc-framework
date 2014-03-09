<?php

class Signup_Model extends Base_Model {

    // Get existing users in DB by email
    private function getUserByEmail( $email ) {

        // Sanitize data and add quotes
        $email = $this->db->getQuotedValue($email);

        // Retrieve users with the same email
        $this->query( "SELECT * FROM users WHERE email = $email");

        // Return users
        return $this->db->fetchNext();
    }

    /** Add user in DB
     * 
     * @param type $email (NOT NULL, UNIQUE)
     * @param type $username (NOT NULL)
     * @param type $password
     * 
     * @return int (DB id or 0 if problem)
     */
    public function addUser( $email, $username, $password ) {

        // Manage empty values and special characters
        $email    = $this->db->getQuotedValue($email);
        $username = $this->db->getQuotedValue($username);
        $password = $this->db->getQuotedValue($password);

        // Doing the job and return user id in case of success
        if ( $this->query( "INSERT INTO users (email, username, password) VALUES ($email, $username, $password)" ) )
            return $this->db->getInsertId();

        // No success
        return 0;
    }
}
