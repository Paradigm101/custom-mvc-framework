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
        $types = array( array( 'attack'     => 'primary', 'health'     => 'secondary', 'experience' => 'ternary', 'magic'  => 'fournary' ),
                        array( 'attack'     => 'primary', 'experience' => 'secondary', 'health'     => 'ternary', 'magic'  => 'fournary' ),
                        array( 'magic'      => 'primary', 'health'     => 'secondary', 'experience' => 'ternary', 'attack' => 'fournary' ), 
                        array( 'magic'      => 'primary', 'experience' => 'secondary', 'health'     => 'ternary', 'attack' => 'fournary' ),
                        array( 'experience' => 'primary', 'attack'     => 'secondary', 'health'     => 'ternary', 'magic'  => 'fournary' ),
                        array( 'experience' => 'primary', 'magic'      => 'secondary', 'attack'     => 'ternary', 'health' => 'fournary' ) );

        $bonuses = array();
        foreach ( range(0, 7) as $primary )
        {
            foreach ( range(0, max( $primary - 1, 0 ) ) as $secondary )
            {
                foreach ( range(0, max( $secondary - 1, 0 ) ) as $ternary )
                {
                    foreach ( range(0, max( $ternary - 1, 0 ) ) as $fournary )
                    {
                        $amounts = array( 'primary'   => $primary,
                                          'secondary' => $secondary,
                                          'ternary'   => $ternary,
                                          'fournary'  => $fournary );

                        foreach ( $types as $type )
                        {
                            $bonuses[] = array( 'attack'     => $amounts[$type['attack']],
                                                'health'     => $amounts[$type['health']],
                                                'experience' => $amounts[$type['experience']],
                                                'magic'    => $amounts[$type['magic']] );
                        }
                    }
                }
            }
        }

        // Remove duplicate key (option to manage arrays)
        $bonuses = array_unique($bonuses, SORT_REGULAR);

        $query = 'INSERT INTO koth_opponents ( name, label, picture, level, ai_level, start_hp, max_attack, max_health, max_experience, max_magic ) VALUES ';

        $values = array();
        foreach( $bonuses as $bonus )
        {
            foreach ( range(0, 2) as $ai_level )
            {
                $name           = $this->getQuotedValue( ( 3 + $bonus['attack'] ) . '_' . ( 3 + $bonus['health'] ) . '_' . ( 3 + $bonus['experience'] ) . '_' . ( 3 + $bonus['magic'] ) . '_' . $ai_level );
                $label          = $this->getQuotedValue( ( 3 + $bonus['attack'] ) . '_' . ( 3 + $bonus['health'] ) . '_' . ( 3 + $bonus['experience'] ) . '_' . ( 3 + $bonus['magic'] ) . '_' . $ai_level );
                $picture        = $this->getQuotedValue( 'opponent_no_pic' );
                $level          = $this->getQuotedValue( 1 + array_sum( $bonus ) );
                $ai_level       = $this->getQuotedValue( 0 + $ai_level );
                $start_hp       = $this->getQuotedValue( 30 + 15 * array_sum( $bonus ) );
                $max_attack     = $this->getQuotedValue( 3 + $bonus['attack'] );
                $max_health     = $this->getQuotedValue( 3 + $bonus['health'] );
                $max_experience = $this->getQuotedValue( 3 + $bonus['experience'] );
                $max_magic      = $this->getQuotedValue( 3 + $bonus['magic'] );

                $values[] = "( $name, $label, $picture, $level, $ai_level, $start_hp, $max_attack, $max_health, $max_experience, $max_magic )\n";
            }
        }

        $query .= implode(',', $values);
        return $query;
    }
}
