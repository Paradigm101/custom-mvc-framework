<?php

class Table_LIB_Koth_Steps extends Table_LIB_Origin
{
    protected function getTableName()
    {
        return 'koth_steps';
    }

    // Init: without ID
    protected function getInitMode()
    {
        return TLM_INIT_AUTO_WITHOUT_ID;
    }
}
