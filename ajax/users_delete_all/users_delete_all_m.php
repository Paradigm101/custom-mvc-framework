<?php

class Users_Delete_All_AJA_M extends Base_AJA_M
{
    public function deleteAllUsers( $tableName )
    {
        $fields = array( 'users1' => 'u.id',
                         'users2' => 'u.username',
                         'users3' => 'u.email',
                         'users4' => 'r.label' );

        // Filters
        $whereQuery = '';
        foreach ( Url_LIB::getBoardFilters() as $key => $value ) {
            $whereQuery .= " AND {$fields[$key]} like '%$value%' ";
        }

        $query = 'DELETE u '
               . 'FROM users u '
               . '     INNER JOIN roles r ON '
               . '         r.id = u.id_role '
               . '     AND r.name NOT LIKE \'%admin%\''
               . ' WHERE '
               . '     1 = 1 '
               . $whereQuery . ' ';
        
        $this->query($query);
    }
}
