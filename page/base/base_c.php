<?php

// Mother class for all Page controllers
abstract class Base_PAG_C extends Base_LIB_Controller {

    // If no model, use mine
    static protected function getDefaultModel() {

        return 'Base_PAG_M';
    }

    // If no view, use mine
    static protected function getDefaultView() {

        return 'Base_PAG_V';
    }

    // Core method, parent has to called in children
    static protected function process() {

        // Call parent (just in case)
        parent::process();

        // Sending page name for page view
        self::$view->setPageName( strtolower( str_replace( '_PAG_C', '', get_called_class() ) ) );
    }
}
