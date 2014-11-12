<?php

/**
 * Entry point for GET and POST data
 * 
 * TBD: manage security, avoid SQL injection, ...
 * TBD: manage arrays
 * TBD: user filter_input, etc..
 */
abstract class Url_Parser_LIB
{
    static private function getGetParam($param) {

        if ( array_key_exists($param, $_GET))
            return $_GET[ $param ];

        return null;
    }

    static private function getPostParam($param) {

        if ( array_key_exists($param, $_POST))
            return $_POST[ $param ];

        return null;
    }

    static public function getRequestParam( $param ){

        $data = null;

        switch( strtolower($_SERVER['REQUEST_METHOD']) )
        {
            case 'get':
                if ( ( $data = static::getGetParam($param) ) === null )
                    $data = static::getPostParam($param);
                break;

            case 'post':
                if ( ( $data = static::getPostParam($param) ) === null )
                    $data = static::getGetParam($param);
                break;

            default:
                Log_LIB::trace( $_SERVER, '[Url_Parser_LIB] Unknown server request method : ' . $_SERVER['REQUEST_METHOD'] );
        }
        
        return $data;
    }
}
