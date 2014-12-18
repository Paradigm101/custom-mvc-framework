<?php

// Manage users
abstract class Users_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        $batchActions = array( array( 'delete' ),
                               array( 'change' ),
                               array( 'delete' ),
                               array( 'delete' ),
                               array( 'delete' ) );

        // Create board
        $board = new Board_LIB( 'users',
                                'page/users/fields.csv',
                                'page/users/actions.csv',
                                static::$model->getBoardQuery(),
                                static::$model->getBoardDefaultSort(),
                                'No users' );

        // Send board to view
        static::$view->assign('board', $board);
    }
}
