<?php

// Manage user sign-up
class Signup_AJA_M extends Base_AJA_M {

    /** Add user in DB
     *      return 0 (problem) or Id user
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
