<?php

// Table users
class Users_Table_Model extends Base_Table_Model {

    // Table data
    protected function initTable() {

        // Name
        $this->tableName = 'users';

        // Fields
        $this->addParameter('id',       'int',      11, false, null, true, true);
        $this->addParameter('email',    'varchar', 100);
        $this->addParameter('username', 'varchar', 100);
        $this->addParameter('password', 'varchar', 100);

        // Email is unique
        $this->setUnique( 'email' );
    }
}
