<?php

/**
 * TV show page model
 */
class Tv_Show_PAG_M extends Base_PAG_M {

    // Get query for data used in the board
    public function getQueryForBoard() {

        // The nice query
        $query = 'SELECT * FROM users;';

        return $query;
    }
}
