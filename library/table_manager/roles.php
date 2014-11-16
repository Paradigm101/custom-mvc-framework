<?php

// Table users
class Table_manager_LIB_Roles extends Table_manager_LIB_Base {

    // Table data
    protected function initTable() {

        // Name
        $this->tableName = 'roles';

        // Fields
        $this->addParameter('id',    'int',      11, false, null, true, true);
        $this->addParameter('name',  'varchar', 100);
        $this->addParameter('label', 'varchar', 100);
        
        // Init query for config
        $this->initQuery = "INSERT INTO roles (name, label) VALUES ('admin', 'administrator'),"
                                                                . "('guest', 'guest'),"
                                                                . "('role1', 'role 1'),"
                                                                . "('role2', 'role 2');";
    }
}
