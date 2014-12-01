<?php

// User manager page
abstract class Users_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        // Create board
        $board = new Board_LIB( static::$model->getData(), 'page/users/users.csv', 'No users' );

        // Send board to front
        static::assign('board', $board);
    }
}
