<?php

/**
 * Starting/Main page
 */
abstract class Session_Page_Controller extends Base_Page_Controller {

    static protected function process() {

          unset($_SESSION['count']);
//        if (!isset($_SESSION['count'])) {
//          $_SESSION['count'] = 0;
//        } else {
//          $_SESSION['count']++;
//        }

        static::$view->assign('session_count99', $_SESSION['count']);
        $_SESSION['count']++;
        Log_Library_Controller::trace($a++);
        Log_Library_Controller::trace($a++);
        static::$view->assign('session_count0', $_SESSION['count']);
        static::$view->assign('session_count1', $_SESSION['count']++);
        static::$view->assign('session_count2', $_SESSION['count']++);
        static::$view->assign('session_count3', $_SESSION['count']++);
    }
}
