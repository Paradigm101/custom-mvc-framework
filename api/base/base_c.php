<?php

/**
 * Mother class for all api controllers
 */
abstract class Base_API_C extends Base_LIB_Controller {

    // If no model, use mine
    static protected function getDefaultModel() {

        return 'Base_API_M';
    }

    // If no view, use mine
    static protected function getDefaultView() {

        return 'Base_API_V';
    }
}
