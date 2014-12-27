<?php

// Table roles
class Table_LIB_Koth_Die_Types extends Table_LIB_Origin {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'koth_die_types';
    }

    // Init: without ID
    protected function getInitMode() {

        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
