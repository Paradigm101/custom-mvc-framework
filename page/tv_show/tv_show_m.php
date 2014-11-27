<?php

/**
 * TV show page model
 */
class Tv_Show_PAG_M extends Base_LIB_Model {

    // Get query for data used in the board
    public function getQueryForBoard() {

        // The nice query
        $query = 'SELECT '
                . '     id      `id@roles`, '
                . '     name    `name@roles`, '
                . '     label   `label@roles` '
                . 'FROM '
                . '     roles;';

        return $query;
    }
}
