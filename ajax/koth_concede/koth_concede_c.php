<?php

abstract class Koth_Concede_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process() {

        static::$model->concedeGame( Session_LIB::getUserId() );
    }
}
