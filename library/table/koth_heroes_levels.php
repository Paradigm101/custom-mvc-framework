<?php

class Table_LIB_Koth_Heroes_Levels extends Table_LIB_Origin {

    protected function getTableName() {

        return 'koth_heroes_levels';
    }
    
    protected function getInitMode()
    {
        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
