<?php

// Manage data for board
class Board_LIB_Model extends Model_Base_LIB {

    // Data from DB
    private $data;

    // Constructor: retrieve data and store them
    public function __construct( $query = '' ) {

        // Parent, obv
        parent::__construct();

        // Query data
        $this->query( $query );

        // Set data ready to retrieve
        $this->data = $this->fetchAll( 'array' );
    }

    // Retrieve data
    public function getBoardData() {

        return $this->data;
    }
}
