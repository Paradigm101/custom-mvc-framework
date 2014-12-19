<?php

// Table roles
class Table_LIB_Surnames extends Table_LIB_Origin {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'surnames';
    }

    // Init: without ID
    protected function getInitMode() {

        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
