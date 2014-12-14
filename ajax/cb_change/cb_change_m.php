<?php

// Manage checkbox change for a board
class Cb_Change_AJA_M extends Base_AJA_M {

    public function storeCheckboxChange( $idCb, $isChecked, $tableName ) {

        // Sanitize data and add quotes
        $idCb      = $this->getQuotedValue($idCb);
        $tableName = $this->getStringForQuery($tableName);  // Careful

        // Checking a checkbox: add in temporary table table
        if ( $isChecked ) {

            $query = "INSERT INTO `$tableName` ( id_item ) VALUES ( $idCb ) ";
        }
        // Unchecking checkbox: remove from table
        else {
            
            $query = "DELETE FROM `$tableName` WHERE id_item = $idCb ";
        }

        $this->query($query);
    }
}
