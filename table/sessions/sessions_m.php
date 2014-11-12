<?php

// Table sessions
class Sessions_Table_Model extends Base_Table_Model {

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
