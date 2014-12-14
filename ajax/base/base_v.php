<?php

// Generic answer for ajax
class Base_AJA_V extends Base_LIB_View {

    // send answer JSON
    public function render() {

        // Using JSON
        header("content-type:application/json");

        // Converting and sending data
        echo json_encode( $this->getData() );
    }
}
