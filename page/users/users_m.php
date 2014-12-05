<?php

// Users management model
class Users_PAG_M extends Board_LIB_Model {

    protected function getBoardDefaultSort() {
        
        return 'users2';
    }

    protected function getBoardQuery() {
        
        $fields = array( 'users1' => 'u.id',
                         'users2' => 'u.username',
                         'users3' => 'u.email',
                         'users4' => 'r.label' );
        
        // Filters
        $whereQuery = ' WHERE 1 = 1 ';
        foreach ( Url_LIB::getBoardFilter() as $key => $value ) {
            $whereQuery .= " AND {$fields[$key]} like '%$value%' ";
        }

        return 'SELECT '
                . '     u.id        users1, '
                . '     u.username  users2, '
                . '     u.email     users3, '
                . '     r.label     users4 '
                . 'FROM '
                . '     users u '
                . '     INNER JOIN roles r ON '
                . '         r.id = u.id_role '
                . $whereQuery . ' ';
    }
}
