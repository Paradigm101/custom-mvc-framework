<?php

/**
 * Session page: to test how session works and create login/logout system
 */
abstract class Session_Page_Controller extends Base_Page_Controller {

    static protected function process() {

        static::$view->assign('session_id', session_id());
    }
}
