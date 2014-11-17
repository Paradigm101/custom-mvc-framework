<?php

// Page Manager: manage data displayed in the requested page        
abstract class Page_Manager_LIB {

    // Page list (should be const but seems impossible to be private and const for whatever reason...)
    static private $pages = array( array( 'fileName' => 'main',          'shortcut' => 'h', 'withCtrl' => true, 'headerTitle' => '<strong>H</strong>ome',       'description' => 'Home page : menu' ),
                                   array( 'fileName' => 'tv_show',       'shortcut' => 's', 'withCtrl' => true, 'headerTitle' => 'TV <strong>s</strong>how',    'description' => 'TV shows' ),
                                   array( 'fileName' => 'bootstrapdemo', 'shortcut' => 'b', 'withCtrl' => true, 'headerTitle' => '<strong>B</strong>ootstrap',  'description' => 'Bootstrap demonstration page' ),
                                   array( 'fileName' => 'api',           'shortcut' => 'p', 'withCtrl' => true, 'headerTitle' => 'A<strong>P</strong>I',        'description' => 'API access' ),
                                   array( 'fileName' => 'about',         'shortcut' => 'o', 'withCtrl' => true, 'headerTitle' => 'Ab<strong>o</strong>ut',      'description' => 'About page : my resume' ) );

    // Return a safe copy of pages to be sure it is not modified by the system
    static public function getAllPages() {
        
        return Tools_LIB::safeClone(self::$pages);
    }

    // Get page list for current user
    static public function getUserPages() {

        // Initialize accessible pages
        $pages = array();

        // For every existing page (safeClone to avoid any changes)
        foreach( static::getAllPages() as $page ) {

            // Check access
            if ( Session_Manager_LIB::hasAccess($page['fileName']) ) {

                // Add page to the accessible pages
                $pages[] = $page;
            }
        }

        // Send pages
        return $pages;
    }

    /****************************************************************************************************************/
    
    // Manage javascript to inject in page
    static private $classWithJavascript = array( 'Url_Manager_LIB' );

    // Send javascript to client
    static public function getJavascriptForPage() {

        $script = "";

        // Constantes
        //-----------
        $script .= "// Constantes\n"
                . "//-----------\n"
                . "REQUEST_TYPE_AJAX = " . REQUEST_TYPE_AJAX . ";\n"
                . "REQUEST_TYPE_PAGE = " . REQUEST_TYPE_PAGE . ";\n"
                . "\n";

        // Retrieve javascript from each subscribed class
        foreach ( static::$classWithJavascript as $class ) {
            $script .= $class::getJavascript() . "\n\n";
        }

        // Add shortcuts
        //--------------
        $script .= "// Shortcuts\n"
                . "//----------\n"
                . "$(function () {\n"
                .  "    $(document).keypress(function(e) {\n";

        foreach ( static::getUserPages() as $page ) {

            $shortcutCondition = 'e.which == ' . ord( $page['shortcut'] ) . ( $page['withCtrl'] ? ' && e.ctrlKey' : '' );

            $script .= "        if ( $shortcutCondition ) {\n"
                    . "             e.preventDefault();\n"
                    . "             $(location).attr('href', '" . Url_Manager_LIB::getUrlForRequest( $page['fileName'] ) . "' );\n"
                    . "         }\n";
        }

        $script .= "    });\n"
                . "});\n\n";

        return $script;
    }

    /****************************************************************************************************************/

    // Manage time spent in DB for page display
    static private $DBTime = 0;

    static public function addTimeInDB( $time ) {
        static::$DBTime += $time;
    }

    // Starting time in PHP
    static private $pageStartingTime = 0;

    static public function setStartingTime() {
        static::$pageStartingTime = microtime( true );
    }

    // Time spent waiting for another server
    static private $curlTime = 0;

    // Add time spent waiting for another server
    static public function addTimeInCurl( $time ) {

        static::$curlTime += $time;
    }
    
    // Get page generation time (in PHP/DB)
    static public function getPageGeneration() {

        // Get page generation time
        $generationTime = microtime( true ) - static::$pageStartingTime;
        
        // Get time spent in PHP (i.e. not in DB nor waiting for 3rd party server)
        $PHPTime = $generationTime - static::$DBTime - static::$curlTime;

        // Get percentages for display
        $PHPPercent  = round( ( $PHPTime * 100 ) / $generationTime );
        $DBPercent   = round( ( static::$DBTime * 100 ) / $generationTime );
        $CurlPercent = round( ( static::$curlTime * 100 ) / $generationTime );

        // Send data to display
        return 'Page Generation Time: ' . round($generationTime, 3) . "s (PHP: $PHPPercent% - SQL: $DBPercent%" . ( static::$curlTime ? " - CURL: $CurlPercent%" : "" ) . ')';
    }
}
