<?php

/**
 * Entry point for GET and POST data
 * 
 * TBD: manage security, avoid SQL injection, etc...
 */
abstract class Urlparser_Library
{
    static public function getRequestParam( $param )
    {
        switch( strtolower($_SERVER['REQUEST_METHOD']) )
        {
            case 'get':
                if ( !array_key_exists($param, $_GET))
                    return null;
                return $_GET[ $param ];
                break;
            
            case 'post':
                if ( !array_key_exists($param, $_POST))
                    return null;
                return $_POST[ $param ];
                break;

            default:
                Error_Library::launch( 'Unknow server request method : ' . $_SERVER['REQUEST_METHOD'] );
                exit();
        }
    }
}
