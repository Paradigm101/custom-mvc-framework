<?php

// Mother of all view/answer for pages/apis/ajaxs
abstract class Base_LIB_View
{
    // Holds data/answer
    private $data = array();

    // For view
    protected function getData()
    {
        return $this->data;
    }

    // Push data from controller
    public function assign( $key, $value )
    {
        $this->data[ $key ] = $value;
    }

    // display page (send answer)
    abstract public function render();
}
