<?php

// Class to manage pages displayed in website
abstract class Menu_LIB {

    // Define menu pages
    static public function getPageMenu() {

        return array( 1 => array( 'shortcut' => 'h', 'withControl' => true, 'fileName' => 'main',          'pageName' => '<strong>H</strong>ome' ),
                      2 => array( 'shortcut' => 'b', 'withControl' => true, 'fileName' => 'bootstrapdemo', 'pageName' => '<strong>B</strong>ootstrap' ),
                      3 => array( 'shortcut' => 's', 'withControl' => true, 'fileName' => 'session',       'pageName' => 'S<strong>e</strong>ssion' ),
                      4 => array( 'shortcut' => 't', 'withControl' => true, 'fileName' => 'table',         'pageName' => '<strong>T</strong>able' ),
                      5 => array( 'shortcut' => 'p', 'withControl' => true, 'fileName' => 'api',           'pageName' => 'A<strong>P</strong>I' ),
                      6 => array( 'shortcut' => 'a', 'withControl' => true, 'fileName' => 'about',         'pageName' => '<strong>A</strong>bout' ) );
    }
}
