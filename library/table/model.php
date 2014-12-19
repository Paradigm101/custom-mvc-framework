<?php

class Table_LIB_Model extends Base_LIB_Model {
    
    // Delete all temporary tables (that starts with 'Tmp_')
    public function deleteTemporaryTables() {

        $this->query( 'SELECT * FROM information_schema.tables' );

        while( $table = $this->fetchNext() )
        {
            if (strtolower( substr($table->TABLE_NAME, 0, 4) ) == 'tmp_' )
            {
                $this->query( 'DROP TABLE ' . $table->TABLE_NAME );
            }
        }
    }
}
