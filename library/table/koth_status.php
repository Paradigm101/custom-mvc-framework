<?php

class Table_LIB_Koth_Status extends Table_LIB_Origin
{
    protected function getTableName()
    {
        return 'koth_status';
    }

    // Init: without ID
    protected function getInitMode()
    {
        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
