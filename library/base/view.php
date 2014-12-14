<?php

// Mother of all view/answer for pages/apis/ajaxs
abstract class Base_LIB_View {

    // Holds data/answer
    private $data = array();

    // For view
    protected function getData() {
        return $this->data;
    }

    // Push data from controller
    public function assign( $key, $value ) {

        $this->data[ $key ] = $value;
    }

    // display page (send answer)
    public function render() {

        // No default, has to be overwritter
        Log_LIB::trace('[Base_LIB_View] Method ' . __METHOD__ . ' has to be overwritten from [' . get_called_class() . ']');
    }
}
