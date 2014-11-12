<?php

abstract class Menu_LIB {

    // Define menu pages (unused for the moment TBD)
    static public function getPageMenu() {

        return array( array( 'rank' => 1, 'shortcut' => 'h', 'withControl' => true, 'fileName' => 'main',          'pageName' => 'Home' ),
                      array( 'rank' => 2, 'shortcut' => 'b', 'withControl' => true, 'fileName' => 'bootstrapdemo', 'pageName' => 'Bootstrap' ),
                      array( 'rank' => 3, 'shortcut' => 's', 'withControl' => true, 'fileName' => 'session',       'pageName' => 'Session' ),
                      array( 'rank' => 4, 'shortcut' => 't', 'withControl' => true, 'fileName' => 'table',         'pageName' => 'Table' ),
                      array( 'rank' => 5, 'shortcut' => 'a', 'withControl' => true, 'fileName' => 'about',         'pageName' => 'About' ) );
    }
}
