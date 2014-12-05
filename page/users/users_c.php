<?php

// User manager page
abstract class Users_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        // Create board
        $board = new Board_LIB();

        // Set all information
        $board->setData(static::$model->getBoardData())
                ->setMetadata('page/users/users.csv')
                ->setNoDataMessage('No users')
                ->setPageNumber(static::$model->getBoardPageNumber())
                ->setRequestName('users')
                ->setSort(static::$model->getBoardSort())
                ->setCurrentPage(static::$model->getBoardCurrentPage())
                ->setFilters(static::$model->getBoardFilters());

        // Send board to view
        static::assign('board', $board);
    }
}
