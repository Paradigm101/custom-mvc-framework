<?php

// Manage users
abstract class Users_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        // Create board
        $board = new Board_LIB( 'users',
                                'page/users/users.csv',
                                static::$model->getBoardQuery(),
                                static::$model->getBoardDefaultSort(),
                                'No users' );

        // Send board to view
        static::$view->assign('board', $board);
    }
}
