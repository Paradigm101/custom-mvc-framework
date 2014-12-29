<?php

class Table_LIB_Koth_User_Xp extends Table_LIB_Origin
{
    protected function getTableName()
    {
        return 'koth_user_xp';
    }
    
    protected function getInitMode()
    {
        return TLM_INIT_CUSTOM;
    }

    // SQL script to populate the table
    protected function getInitScript()
    {
        return <<<EOD
INSERT INTO
    koth_user_xp (
        id_user,
        experience,
        level
    )
SELECT
    id,
    0,
    1
FROM
    users
EOD;
    }
}
