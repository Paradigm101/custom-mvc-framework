<?php

// Users management model
class Users_PAG_M extends Base_LIB_Model {
    
    // Get data to display
    public function getData() {

        // Get sort
        if ( !($sort = Url_LIB::getRequestParam('s') ) ) {

            $sort = 'c2';
        }

        // TBD get filter param

        // Query to get DB data
        $query = 'SELECT '
                . '     u.id        c1, '
                . '     u.username  c2, '
                . '     u.email     c3, '
                . '     r.label     c4 '
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
