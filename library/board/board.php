<?php

// Providing a nice board
class Board_LIB {

    // Manage board data
    private $model;

    // Manage board display
    private $view;

    // Nice constructor
    public function __construct( $query = null, $fields = array() ) {

        // No query, get error for dev and show must go on
        if ( !$query ) {

            $backTrace = debug_backtrace();
            Log_LIB::trace('[Board_LIB __construct] Query is not set for class [' . $backTrace[1]['class'] . ']');
        }

        // Create model
        $this->model = new Board_LIB_Model( $query );

        // Create view
        $this->view = new Board_LIB_View( $this->model->getBoardData(), $fields );
    }

    // Display board (for template)
    public function display() {

        // Return board display
        return $this->view->display();
    }
}
