<?php

/**
 * Manage url related services
 *      retrieve GET and POST data
 *      create url
 * 
 * TBD: manage security, avoid SQL injection, ...
 * TBD: manage arrays in URL
 * TBD: user filter_input, etc..
 * 
 * URL parameters:
 *      - rn: request name, name for the page, ajax or api
 *      - rt: request type, page, ajax or api REQUEST_TYPE_XXX (null = page)
 *      - s: sort parameter (for board)
 *      - f_<xxx>: filter of variable <xxx> (for board)
 *      - p: page (for board)
 */
abstract class Url_LIB {

    // Send javascript for client
    static public function getJavascript() {

        // Create URL from request name/type
        $script  = "// get URL for a specific page\n"
                .  "//----------------------------\n"
                .  "var getURL = function( request_name, request_type ) {\n"
                .  "\n"
                .  "    if ( request_type == undefined ) {\n"
                .  "\n"
                .  "        if ( request_name == undefined ) {\n"
                .  "\n"
                .  "            return '" . SITE_ROOT . "';\n"
                .  "        }\n"
                .  "\n"
                .  "        return '" . SITE_ROOT . "/' + request_name;\n"
                .  "    }\n"
                .  "\n"
                .  "    return '" . SITE_ROOT . "?rn=' + request_name + '&rt=' + request_type;\n"
                .  "}\n";

        return $script;
    }

    // Get URL for request
    static public function getUrlForRequest( $requestName = null, $requestType = null ) {

        // URL according to type
        switch ( $requestType ) {

            // Ajax
            case REQUEST_TYPE_AJAX:
                return SITE_ROOT . '?rn='. $requestName . '&rt=' . $requestType;

            // API
            case REQUEST_TYPE_API:
                return SITE_ROOT . '/api/' . $requestName . '/';

            // Others/pages
            case REQUEST_TYPE_PAGE:
            default:
                return SITE_ROOT . '/' . $requestName;
        }
    }

    // Get a parameter through GET method
    static private function getGetParam($param) {

        if ( array_key_exists($param, $_GET)) {

            return $_GET[ $param ];
        }

        return null;
    }

    // Get a parameter through POST method
    static private function getPostParam($param) {

        if ( array_key_exists($param, $_POST) ) {

            return $_POST[ $param ];
        }

        return null;
    }

    // Get a parameter from a request
    static public function getRequestParam( $param ) {

        $data = null;

        switch( strtolower($_SERVER['REQUEST_METHOD']) )
        {
            case 'get':
                if ( ( $data = static::getGetParam($param) ) === null ) {

                    $data = static::getPostParam($param);
                }
                break;

            case 'post':
                if ( ( $data = static::getPostParam($param) ) === null ) {

                    $data = static::getGetParam($param);
                }
                break;

            default:
                Log_LIB::trace( $_SERVER, '[Url_LIB] Unknown server request method : ' . $_SERVER['REQUEST_METHOD'] );
        }
        
        return $data;
    }
    
    // Specific board: retrieve filters
    static public function getBoardFilters() {
        
        $filters = array();

        // Retrieving GET filters
        foreach ( $_GET as $key => $value ) {

            if ( substr( $key, 0, 2) == 'f_' ) {

                $filters[ substr( $key, 2) ] = $value;
            }
        }

        // Retrieving POST filters
        foreach ( $_POST as $key => $value ) {

            if ( substr( $key, 0, 2) == 'f_' ) {

                $filters[ substr( $key, 2) ] = $value;
            }
        }

        return $filters;
    }
}
