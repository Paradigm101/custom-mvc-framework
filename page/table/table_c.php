<?php

/**
 * Table page controller
 */
abstract class Table_Page_Controller extends Base_Page_Controller {

    static protected function process() {

        // Create the nice table
        $table = new Table_Library_Controller();

        // Send table to front
        static::$view->assign('table', $table);
    }
}
