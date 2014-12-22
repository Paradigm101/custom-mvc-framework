<?php

// Heroes for King of the Hill
class Table_LIB_Koth_Heroes extends Table_LIB_Origin {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'koth_heroes';
    }
    
    // Return initializing mode, can be overwritten
    protected function getInitMode() {

        // By default, no initialization
        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
