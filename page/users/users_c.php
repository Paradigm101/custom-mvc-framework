<?php

// User manager page
abstract class Users_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        // Retrieving information
        list( $data, $resultNumber, $currentPage, $pageNumber ) = static::$model->getData();

        // Create board
        $board = new Board_LIB( $data, $resultNumber, $currentPage, $pageNumber, 'users', 'page/users/users.csv', 'No users' );

        // Send board to front
        static::assign('board', $board);
    }
}
