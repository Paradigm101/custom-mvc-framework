<?php

class Table_LIB_Koth_Heroes_Levels extends Table_LIB_Origin {

    protected function getTableName() {

        return 'koth_heroes_levels';
    }

    protected function getInitMode()
    {
        return TLM_INIT_CUSTOM;
    }

    protected function getInitScript()
    {
        $heroes = array( array( 'id' => 1, 'attack' => 'primary',     'health' => 'secondary',     'experience' => 'ternary', 'victory' => 'fournary' ),
                         array( 'id' => 2, 'attack' => 'primary',     'experience' => 'secondary', 'health' => 'ternary',     'victory' => 'fournary' ),
                         array( 'id' => 3, 'victory' => 'primary',    'health' => 'secondary',     'experience' => 'ternary', 'attack' => 'fournary' ), 
                         array( 'id' => 4, 'victory' => 'primary',    'experience' => 'secondary', 'health' => 'ternary',     'attack' => 'fournary' ),
                         array( 'id' => 5, 'experience' => 'primary', 'attack' => 'secondary',     'health' => 'ternary',     'victory' => 'fournary' ),
                         array( 'id' => 6, 'experience' => 'primary', 'victory' => 'secondary',    'attack' => 'ternary',     'health' => 'fournary' ) );

        $bonuses = array( array( 'primary' => 0, 'secondary' => 0, 'ternary' => 0, 'fournary' => 0 ),
                          array( 'primary' => 1, 'secondary' => 0, 'ternary' => 0, 'fournary' => 0 ),
                          array( 'primary' => 2, 'secondary' => 0, 'ternary' => 0, 'fournary' => 0 ),
                          array( 'primary' => 2, 'secondary' => 1, 'ternary' => 0, 'fournary' => 0 ),
                          array( 'primary' => 3, 'secondary' => 1, 'ternary' => 0, 'fournary' => 0 ),
                          array( 'primary' => 3, 'secondary' => 2, 'ternary' => 0, 'fournary' => 0 ),
                          array( 'primary' => 3, 'secondary' => 2, 'ternary' => 1, 'fournary' => 0 ) );

        $query = 'INSERT INTO koth_heroes_levels ( id_hero, level, start_hp, max_attack, max_health, max_experience, max_victory ) VALUES ';

        $values = array();
        foreach ( $heroes as $hero )
        {
            foreach( $bonuses as $bonus )
            {
                $id_hero        = $this->getQuotedValue( 0 + $hero['id'] );
                $level          = $this->getQuotedValue( 1 + array_sum( $bonus ) );
                $start_hp       = $this->getQuotedValue( 30 + 2 * array_sum( $bonus ) );
                $max_attack     = $this->getQuotedValue( 3 + $bonus[$hero['attack']] );
                $max_health     = $this->getQuotedValue( 3 + $bonus[$hero['health']] );
                $max_experience = $this->getQuotedValue( 3 + $bonus[$hero['experience']] );
                $max_victory    = $this->getQuotedValue( 3 + $bonus[$hero['victory']] );

                $values[] = "( $id_hero, $level, $start_hp, $max_attack, $max_health, $max_experience, $max_victory )";
            }
        }

        $query .= implode(',', $values);
        return $query;
    }
}
