<?php

/**
 * TV show page controller
 */
abstract class Tv_Show_PAG_C extends Base_PAG_C {

    // interface between PHP code and front-end
    static private $frontFields = array( 'idRole'    => array( 'label' => 'role ID' ),
                                         'roleName'  => array( 'label' => 'role name' ),
                                         'roleLabel' => array( 'label' => 'role label' ) );

    // Main process
    static protected function process() {

        // Query to get DB data
        $query = 'SELECT '
                . '     id      idRole, '
                . '     name    roleName, '
                . '     label   roleLabel '
                . 'FROM '
                . '     roles;';

        // Create board
        $board = new Board_LIB( $query, static::$frontFields );

        // Send board to front
        static::assign('board', $board);
    }
}
