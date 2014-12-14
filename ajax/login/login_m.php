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
}
