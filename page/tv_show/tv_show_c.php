<?php

/**
 * TV show page controller
 */
abstract class Tv_Show_PAG_C extends Base_PAG_C {

    // Main process
    static protected function process() {

        // Do stuff
        $list = static::$model->getTVShowList();

        static::assign('list', $list);
    }
}
