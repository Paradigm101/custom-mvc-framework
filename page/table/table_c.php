<?php

/**
 * Table page controller
 */
abstract class Table_PAG_C extends Base_PAG_C {

    static protected function process() {

        // Create the nice board
        $board = new Board_LIB();

        // Send table to front
        static::assign('board', $board);
    }
}
