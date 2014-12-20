<?php

class Users_Delete_Item_AJA_M extends Base_AJA_M
{
    public function deleteUser( $idItem )
    {
        $idItem = $this->getQuotedValue($idItem);
        
        $this->query( "DELETE FROM users WHERE id = $idItem;" );
    }
}
