<?php

// Table users
class Table_LIB_Users extends Table_LIB_Origin {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'users';
    }
    
    protected function getInitMode() {

        return TLM_INIT_CUSTOM;
    }
    
    // SQL script to populate the table
    protected function getInitScript() {
        
        // Big database
        if ( DATABASE_IS_BIG ) {

            return $this->getBigDBScript();
        }
        
        // Small database
        return $this->getSmallDBScript();
    }
    
    private function getBigDBScript() {
        
        // Start and add first user (admin)
        $query = "INSERT INTO users ("
                    . "email, "
                    . "username, "
                    . "password, "
                    . "id_role"
                . ") "
                . "SELECT "
                . "     LOWER( CONCAT( m.name, '_', s.name, '@fakemail.com' ) ), "
                . "     LOWER( CONCAT( SUBSTRING( m.name, 1, 1 ), s.name ) ), "
                . "     't', "
                . "     3 "
                . "FROM "
                . "     surnames s, "
                . "     male_first_names m "
                . "UNION "
                . "SELECT "
                . "     LOWER( CONCAT( f.name, '_', s2.name, '@fakemail.com' ) ), "
                . "     LOWER( CONCAT( SUBSTRING( f.name, 1, 1 ), s2.name ) ), "
                . "     't', "
                . "     3 "
                . "FROM "
                . "     surnames s2, "
                . "     female_first_names f ;";

        return $query;
    }
    
    private function getSmallDBScript() {
        
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
        $query = "INSERT INTO users ("
                    . "email, "
                    . "username, "
                    . "password, "
                    . "id_role"
                . ") "
                . "VALUES ( 't', 't', 't', 1 ),( 'r', 'r', 'r', 1 ),";

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
