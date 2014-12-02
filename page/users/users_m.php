<?php

// Users management model
// TBD Filters
class Users_PAG_M extends Board_LIB_Model {

    protected function getBoardDefaultSort() {
        
        return 'c2';
    }

    protected function getBoardQuery() {
        
        return 'SELECT '
                . '     u.id        c1, '
                . '     u.username  c2, '
                . '     u.email     c3, '
                . '     r.label     c4 '
                . 'FROM '
                . '     users u '
                . '     INNER JOIN roles r ON '
                . '         r.id = u.id_role ';
    }
}
