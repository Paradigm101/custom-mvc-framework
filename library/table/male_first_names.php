<?php

// Table roles
class Table_LIB_Male_First_Names extends Table_LIB_Origin {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'male_first_names';
    }

    // Init: without ID
    protected function getInitMode() {

        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
