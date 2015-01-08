<?php

class Clients_PAG_M extends Base_PAG_M
{
    public function getBoardDefaultSort() {

        return 'clients6';
    }

    public function getBoardQuery() {
        
        $fields = array( 'clients1' => 'c.id',
                         'clients2' => 'c.username',
                         'clients5' => 'c.first_name',
                         'clients6' => 'c.last_name',
                         'clients3' => 'c.email',
                         'clients4' => 'r.label' );

        // Filters
        $whereQuery = '';
        foreach ( Url_LIB::getBoardFilters() as $key => $value ) {
            $whereQuery .= " AND {$fields[$key]} like '%$value%' ";
        }

        return 'SELECT '
                . '     c.id          clients1, '
                . '     c.username    clients2, '
                . '     c.first_name  clients5, '
                . '     c.last_name   clients6, '
                . '     c.email       clients3, '
                . '     r.label       clients4 '
                . 'FROM '
                . '     clients c '
                . '     INNER JOIN roles r ON '
                . '         r.id = c.id_role '
                . ' WHERE '
                . '     1 = 1 '
                . $whereQuery . ' ';
    }

    public function getRolesForModification()
    {
        $this->query("SELECT * FROM roles WHERE name != 'admin' AND name != 'guest' ;");
        return $this->fetchAll();
    }
}
