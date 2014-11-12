<?php

abstract class Logout_AJA_C extends Base_AJA_C {

    static protected function process() {

        static::$model->removeCurrentSession();
    }
}
