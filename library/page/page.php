<?php

// Page Manager: manage data displayed in the requested page        
abstract class Page_LIB {

    // Page list (should be const but seems impossible to be private and const for whatever reason...)
    static private $pages;

    // Return a safe copy of pages to be sure it is not modified by the system
    // to be used by every internal methods
    static public function getAllPages() {

        // Initialize pages if needed
        if ( !static::$pages ) {

            // Get config file for pages
            $csvFile = fopen( 'library/page/page.csv', 'r' );

            // Parsing file and storing data
            while ( $page = fgetcsv( $csvFile ) ) {

                // Add page
                static::$pages[] = array( 'fileName'    => trim( $page[0] ),
                                          'shortcut'    => trim( $page[1] ),
                                          'withCtrl'    => trim( $page[2] ) ? true : false,
                                          'headerTitle' => trim( $page[3] ),
                                          'description' => trim( $page[4] ) );
            }
        }

        // Return only a copy to be sure it wouldn't modify by the system
        return Tools_LIB::safeClone(self::$pages);
    }

    // Get page list for current user
    static public function getUserPages() {

        // Initialize accessible pages
        $pages = array();

        // For every existing page (safeClone to avoid any changes)
        foreach( static::getAllPages() as $page ) {

            // Check access
            if ( Session_LIB::hasAccess($page['fileName']) ) {

                // Add page to the accessible pages
                $pages[] = $page;
            }
        }

        // Send pages
        return $pages;
    }

    /****************************************************************************************************************/

    static private $extraScript;
    
    // Others can push extra script
    static public function addJavascript( $script ) {
        
        static::$extraScript .= ";\n" . $script . ";\n";
    }
    
    // Manage javascript to inject in page
    static private $classWithJavascript = array( 'Url_LIB' );

    // Send javascript to client
    static public function getJavascript() {

        // Start script
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

        // Start shortcuts
        //----------------
        $script .= "// Shortcuts\n"
                . "//----------\n"
                . "$(function () {\n"
                .  "    $(document).keypress(function(e) {\n";

        // For each page, add shortcut
        foreach ( static::getUserPages() as $page ) {

            $shortcutCondition = 'e.which == ' . ord( $page['shortcut'] ) . ( $page['withCtrl'] ? ' && e.ctrlKey' : '' );

            $script .= "        if ( $shortcutCondition ) {\n"
                    . "             e.preventDefault();\n"
                    . "             $(location).attr('href', '" . Url_LIB::getUrlForRequest( $page['fileName'] ) . "' );\n"
                    . "         }\n";
        }

        // End shortcuts
        $script .= "    });\n"
                . "});\n\n";

        // Add extra script
        $script .= static::$extraScript;
        
        // Send all
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
