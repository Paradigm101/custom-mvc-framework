<?php

// Manage data for board
class Board_LIB_Model extends Model_Base_LIB {

    // Query to get data
    private $query;

    // Constructor
    public function __construct( $query = '' ) {
        parent::__construct();

        $this->query = $query;
    }

    public function getBoardData() {
        
        $this->query( $this->query );
        
        return $this->fetchAll();
    }
}
