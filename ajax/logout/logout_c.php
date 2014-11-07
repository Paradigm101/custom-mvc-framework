<?php

abstract class Logout_Ajax_Controller extends Base_Ajax_Controller {

    static protected function process() {

        static::$model->removeCurrentSession();
    }
}
