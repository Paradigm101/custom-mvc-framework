<?php

class Login_Ajax_Model extends Base_Library_Model {

    // Log-in user
    public function loginUser( $email, $password ) {

        // Sanitize data and add quotes
        $email    = $this->db->getQuotedValue($email);
        $password = $this->db->getQuotedValue($password);

        // Retrieve users with the same email
        $this->query( "SELECT * FROM users WHERE email = $email AND password = $password");

        // Return users
        return $this->db->fetchNext();
    }
}
