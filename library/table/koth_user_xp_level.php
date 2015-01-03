<?php

class Table_LIB_Koth_User_Xp_Level extends Table_LIB_Origin
{
    protected function getTableName()
    {
        return 'koth_user_xp_level';
    }

    protected function getInitMode()
    {
        return TLM_INIT_CUSTOM;
    }

    protected function getInitScript()
    {
        $query = "INSERT INTO koth_user_xp_level ( level, threshold ) VALUES ";

        $values = array();
        foreach ( range(1, 20) as $level )
        {
            $values[] = "( $level, " . 200 * pow( 2, $level ) . ")";
        }
        
        $query .= implode(',', $values);
        return $query;
    }
}
