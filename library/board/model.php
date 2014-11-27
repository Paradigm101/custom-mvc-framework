<?php

// Manage data for board
class Board_LIB_Model extends Base_LIB_Model {

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
