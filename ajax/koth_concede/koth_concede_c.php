<?php

abstract class Koth_Concede_AJA_C extends Base_AJA_C
{
    // Start a new game
    static protected function process() {

        Koth_LIB::concedeGame( Session_LIB::getUserId() );
    }
}
