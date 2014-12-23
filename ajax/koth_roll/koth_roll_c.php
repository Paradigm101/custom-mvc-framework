<?php

abstract class Koth_Roll_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        static::$model->roll( Session_LIB::getUserId() );
    }
}
