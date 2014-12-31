<?php

// Heroes for King of the Hill
class Table_LIB_Koth_Heroes extends Table_LIB_Origin {

    // Table Name (mandatory)
    protected function getTableName() {

        return 'koth_heroes';
    }
    
    protected function getInitMode()
    {
        return TLM_INIT_AUTO_WITH_ID;
    }
}
