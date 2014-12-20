<?php

class Users_Delete_Batch_AJA_M extends Base_AJA_M
{
    public function deleteSelectedUsers( $tableName )
    {
        $query = 'DELETE us '
                . 'FROM users us '
                . 'INNER JOIN `' . $tableName . '` tmp ON '
                    . ' tmp.id_item = us.id ;';

        $this->query( $query );
    }
}
