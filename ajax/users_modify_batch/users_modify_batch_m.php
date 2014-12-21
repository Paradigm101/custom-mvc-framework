<?php

class Users_Modify_Batch_AJA_M extends Base_AJA_M
{
    public function modifySelectedUsers( $role, $tableName )
    {
        $role = $this->getQuotedValue($role);
        
        $query = 'UPDATE users us '
                . 'INNER JOIN `' . $tableName . '` tmp ON '
                    . ' tmp.id_item = us.id '
                . 'INNER JOIN roles r ON '
                    . "    r.name like $role "
                . 'SET us.id_role = r.id ';

        $this->query( $query );
        
        // Unselected all elements
        $this->query( "TRUNCATE TABLE `$tableName`;" );
    }
}
