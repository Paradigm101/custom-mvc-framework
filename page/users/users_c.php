<?php

// User manager page
abstract class Users_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        // Get sort
        if ( !($sort = Url_LIB::getRequestParam('sort') ) ) {

            $sort = 'email';
        }

        // TBD Get Filter Param

        // Create board
        $board = new Board_LIB( static::$model->getData( $sort ), 'page/users/users.csv', 'No users' );

        // Send board to front
        static::assign('board', $board);
    }
}
