<?php

class Signup_AJA_M extends Base_AJA_M {

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
        $email    = $this->getQuotedValue($email);
        $username = $this->getQuotedValue($username);
        $password = $this->getQuotedValue($password);

        // Doing the job and return user id in case of success
        if ( $this->query( "INSERT INTO users (email, username, password) VALUES ($email, $username, $password)" ) ) {

            return $this->getInsertId();
        }

        // No success
        return 0;
    }
}
