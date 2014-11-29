<?php

// Table users
class Table_LIB_Users extends Table_LIB_Model {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'users';
    }
    
    // Init: without ID
    protected function getInitMode() {

        return TLM_INIT_CUSTOM;
    }
    
    // SQL script to populate the table
    protected function getInitScript() {
        
        $this->query('SELECT * FROM male_first_names');
        $maleFirstNames = $this->fetchAll('array');
        
        $this->query('SELECT * FROM female_first_names');
        $femaleFirstNames = $this->fetchAll('array');
        
        $this->query('SELECT * FROM surnames');
        $surnames = $this->fetchAll('array');
        
        // Let shuffle up
        shuffle($maleFirstNames);
        shuffle($femaleFirstNames);
        shuffle($surnames);
        
        // Start and add first user (admin)
        $query = "INSERT INTO users (email, username, password, id_role) VALUES ( 't', 't', 't', 1 ),";
        
        // Adding a user per surname
        for( $i = 0; $i < count( $surnames ) ; $i++ )
        {
            // Alernatively add a man then a woman
            if ( $i % 2) {
                $name = $maleFirstNames[$i]['name'];
            }
            else {
                $name = $femaleFirstNames[$i]['name'];
            }

            $email     = strtolower( $name . '_' . $surnames[$i]['name'] ) . '@fakemail.com';
            $username  = strtolower( $name[0] . $surnames[$i]['name'] );

            $query .= "( '$email', '$username', 't', " . rand(3, 10) . ' ),';
        }

        // Remove last coma
        $query = substr($query, 0, -1);
        
        return $query;
    }
}
