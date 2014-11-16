<?php

// Table users
class Table_manager_LIB_Users extends Table_manager_LIB_Base {

    // Table data
    protected function initTable() {

        // Name
        $this->tableName = 'users';

        // Fields
        $this->addParameter('id',       'int',      11, false, null, true, true);
        $this->addParameter('email',    'varchar', 100);
        $this->addParameter('username', 'varchar', 100);
        $this->addParameter('password', 'varchar', 100);
        $this->addParameter('id_role',  'int',      11, false, 1 /* admin */ );

        // Email is unique
        $this->setUnique( 'email' );
    }
}
