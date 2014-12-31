<?php

// Opponents for King of the Hill
class Table_LIB_Koth_Opponents extends Table_LIB_Origin
{
    protected function getTableName()
    {
        return 'koth_opponents';
    }

    protected function getInitMode()
    {
        return TLM_INIT_CUSTOM;
    }

    protected function getInitScript()
    {
        $query = 'INSERT INTO koth_opponents ( name, label, picture, level, start_hp, max_attack, max_health, max_experience, max_victory ) VALUES ';

        $values = array();
        $range  = range(3, 7);
        foreach( $range as $attack )
        {
            foreach( $range as $health )
            {
                foreach( $range as $experience )
                {
                    foreach( $range as $victory )
                    {
                        $name     = $this->getQuotedValue( $attack . '_' . $health . '_' . $experience . '_' . $victory );
                        $level    = $this->getQuotedValue( $attack + $health + $experience + $victory - 11 );
                        $start_hp = $this->getQuotedValue( 28 + 2 * $level );
                        $max_a    = $this->getQuotedValue( 0 + $attack );
                        $max_h    = $this->getQuotedValue( 0 + $health );
                        $max_e    = $this->getQuotedValue( 0 + $experience );
                        $max_v    = $this->getQuotedValue( 0 + $victory );

                        $values[] = "( $name, $name, 'no_picture', $level, $start_hp, $max_a, $max_h, $max_e, $max_v )";
                    }
                }
            }
        }

        $values = implode(',', $values);
        return $query . $values;
    }
}
