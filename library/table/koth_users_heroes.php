<?php

class Table_LIB_Koth_Users_Heroes extends Table_LIB_Origin {

    protected function getTableName() {

        return 'koth_users_heroes';
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
    koth_users_heroes (
        id_user,
        id_hero,
        experience,
        level
    )
SELECT
    u.id,
    h.id,
    0,
    1
FROM
    users u,
    koth_heroes h
EOD;
    }
}
