<?php

class Users_PAG_M extends Base_LIB_Model {
    
    public function getData( $sort ) {
        
        // Query to get DB data
        $query = 'SELECT '
                . '     u.id          id, '
                . '     u.username    username, '
                . '     u.email       email, '
                . '     r.label       role '
                . 'FROM '
                . '     users u '
                . '     INNER JOIN roles r ON '
                . '         r.id = u.id_role ';

        // Sort
        if ( $sort ) {
            $query .= "ORDER BY $sort ";
        }

        // End query
        $query .= ';';

        // Query data
        $this->query( $query );

        // Set data ready to retrieve
        return $this->fetchAll( 'array' );
    }
}
