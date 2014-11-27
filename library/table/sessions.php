<?php

// Table sessions
class Table_LIB_Sessions extends Table_LIB_Base {

    // Table data
    protected function initTable() {

        // Name
        $this->tableName = 'sessions';

        // Fields
        $this->addParameter('id_session', 'varchar',    100);
        $this->addParameter('id_user',    'int',         11);
        $this->addParameter('login_date', 'timestamp', null, false, 'CURRENT_TIMESTAMP');
    }
}
