<?php

class Table_LIB_Koth_Hero_Xp_Level extends Table_LIB_Origin
{
    protected function getTableName() {

        return 'koth_hero_xp_level';
    }

    protected function getInitMode()
    {
        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
