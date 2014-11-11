<?php

// Table users
class Users_Table_Model extends Base_Table_Model {

    // Table data
    protected function initTable() {

        // Name
        $this->tableName = 'users';

        // Fields
        $this->addParameter('id',       'int',      11, false, null, true);
        $this->addParameter('email',    'varchar', 100, false);
        $this->addParameter('username', 'varchar', 100, false);
        $this->addParameter('password', 'varchar', 100, false);
    }
}
