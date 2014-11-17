<?php

/**
 * TV show page controller
 */
abstract class Tv_Show_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        // Do stuff
        $query = static::$model->getQueryForBoard();

        // Create board
        $board = new Board_LIB( $query );

        // Send board to front
        static::assign('board', $board);
    }
}
