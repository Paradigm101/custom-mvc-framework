<?php

class Table_LIB_Clients extends Table_LIB_Origin
{
    // Table Name (mandatory)
    protected function getTableName() {

        return 'clients';
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
        $query = "INSERT INTO clients ("
                    . "email, "
                    . "username, "
                    . "first_name, "
                    . "last_name, "
                    . "id_role"
                . ") "
                . "SELECT "
                . "     LOWER( CONCAT( m.name, '_', s.name, '@fakemail.com' ) ), "
                . "     LOWER( CONCAT( SUBSTRING( m.name, 1, 1 ), s.name ) ), "
                . "     m.name, "
                . "     s.name, "
                . "     3 "
                . "FROM "
                . "     surnames s, "
                . "     male_first_names m "
                . "UNION "
                . "SELECT "
                . "     LOWER( CONCAT( f.name, '_', s2.name, '@fakemail.com' ) ), "
                . "     LOWER( CONCAT( SUBSTRING( f.name, 1, 1 ), s2.name ) ), "
                . "     f.name, "
                . "     s2.name, "
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

        // Start
        $query = "INSERT INTO clients ("
                    . "email, "
                    . "username, "
                    . "first_name, "
                    . "last_name, "
                    . "id_role"
                . ") "
                . "VALUES ";

        // Adding a client per surname
        for( $i = 0; $i < count( $surnames ) ; $i++ )
        {
            // Alernatively add a man then a woman
            if ( $i % 2) {
                $name = $maleFirstNames[$i]['name'];
            }
            else {
                $name = $femaleFirstNames[$i]['name'];
            }

            $email      = strtolower( $name . '_' . $surnames[$i]['name'] ) . '@fakemail.com';
            $username   = strtolower( $name[0] . $surnames[$i]['name'] );
            $first_name = strtolower( $name );
            $last_name  = strtolower( $surnames[$i]['name'] );

            $query .= "( '$email', '$username', '$first_name', '$last_name', " . rand(3, 10) . ' ),';
        }

        // Remove last coma
        $query = substr($query, 0, -1);

        return $query;
    }
}
