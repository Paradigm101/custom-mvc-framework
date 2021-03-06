<?php

/**
 * Mother class for all ajax controllers
 */
abstract class Base_AJA_C extends Base_LIB_Controller {

    // If no model, use mine
    static protected function getDefaultModel() {

        return 'Base_AJA_M';
    }

    // If no view, use mine
    static protected function getDefaultView() {

        return 'Base_AJA_V';
    }
    
    // Core method: does nothing (yet)
    static protected function pLaunch() {

        static::process();
    }
    
    // Core method: for children
    static protected function process() {}
}
