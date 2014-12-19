<?php

// Table roles
class Table_LIB_Roles extends Table_LIB_Origin {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'roles';
    }

    // Init: without ID
    protected function getInitMode() {

        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
