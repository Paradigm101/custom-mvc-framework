<?php

class Signup_Model extends Base_Model {

    // Get existing users in DB by email
    private function getUserByEmail( $email ) {

        // Sanitize data and add quotes
        $email = $this->db->getQuotedValue($email);

        // Retrieve users with the same email
        $this->query( "SELECT * FROM users WHERE email = $email");

        // Return users
        return $this->db->fetchAll();
    }

    /** Add user in DB
     * 
     * @param type $email (not null, UNIQUE)
     * @param type $username (not null)
     * @param type $password (nullable)
     * 
     * @return int (DB id or 0 if problem)
     */
    public function addUser( $email, $username, $password ) {

        // Check paramters TBD managed by base
        if ( !$email || !$username ) {
            $this->error = BASE_ERROR_STATUS_NULL_VALUE;
            return 0;
        }

        // Check $email not currently used TBD managed by base
        if ( count($this->getUserByEmail( $email )) ) {
            $this->error = BASE_ERROR_STATUS_DUPLICATE_ENTRY;
            return 0;
        }

        // Manage empty values and special characters
        $email    = $this->db->getQuotedValue($email);
        $username = $this->db->getQuotedValue($username);
        $password = $this->db->getQuotedValue($password);

        // Doing the job
        $success = $this->query( "INSERT INTO users (email, username, password) VALUES ($email, $username, $password)" );

        // Success
        if ( $success )
            return $this->db->getInsertId();

        // No success
        return 0;
    }
}
