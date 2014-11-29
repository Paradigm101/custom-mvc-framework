<?php

class Table_LIB_Female_First_Names extends Table_LIB_Model {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'female_first_names';
    }

    // Init: without ID
    protected function getInitMode() {

        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
