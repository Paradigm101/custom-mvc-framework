<?php

abstract class Koth_Start_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process() {

        static::$model->startGame( Session_LIB::getUserId() );
    }
}
