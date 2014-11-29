<?php

/**
 * TV show page controller
 */
abstract class Users_PAG_C extends Base_PAG_C {

    // interface between PHP code and front-end
    static private $frontFields = array( 'id'       => array( 'label' => 'user ID' ),
                                         'email'    => array( 'label' => 'user email' ),
                                         'username' => array( 'label' => 'username' ),
                                         'role'     => array( 'label' => 'user role' ) );

    // Main process
    static protected function process() {

        // Query to get DB data
        $query = 'SELECT '
                . '     u.id          id, '
                . '     u.email       email, '
                . '     u.username    username, '
                . '     r.label       role '
                . 'FROM '
                . '     users u'
                . '     INNER JOIN roles r ON'
                . '         r.id = u.id_role;';

        // Create board
        $board = new Board_LIB( $query, static::$frontFields );

        // Send board to front
        static::assign('board', $board);
    }
}
