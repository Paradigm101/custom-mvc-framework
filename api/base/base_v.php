<?php

// Generic answer for API
class Base_API_V extends Base_LIB_View {

    // send answer JSON
    public function render() {

        // Using JSON
        header("content-type:application/json");

        // Converting and sending data
        echo json_encode( $this->getData() );
    }
}
