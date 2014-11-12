<?php

/**
 * Table page controller
 */
abstract class Table_PAG_C extends Base_PAG_C {

    static protected function process() {

        // Create the nice table
        $table = new Table_LIB();

        // Send table to front
        static::assign('table', $table);
    }
}
