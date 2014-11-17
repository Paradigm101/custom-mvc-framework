<?php

// Providing a nice board
class Board_LIB {

    // Manage board data
    private $model;

    // Manage board display
    private $view;

    // Nice constructor
    public function __construct( $query = '' ) {

        // Create model and send it the data query
        $this->model = new Board_LIB_Model( $query );

        Log_LIB::trace( $this->model->getBoardData() );

        // Create view
        $this->view = new Board_LIB_View( $this->model->getBoardData() );
    }

    // Display board (for template)
    public function display() {

        return $this->view->display();
    }
}
