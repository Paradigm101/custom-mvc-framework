<?php

/**
 * Log services: show or trace
 */
abstract class Log_Library {

    static private function getEOL( $isWeb ) {
        return ( $isWeb ? ALL_EOL : PHP_EOL );
    }

    // Log data according to type
    static private function coreLog( $param, $title, $isWeb = false ) {

        // Specific display according to data type
        switch ( strtolower( gettype( $param ) ) ) {
            
            case 'array':
            case 'object':
                if ( $isWeb ) {
                    ob_start();
                    var_dump(Tools_Library::safeClone( $param ) );
                    $content = ob_get_contents();
                    ob_end_clean();
                }
                else {
                    $content = print_r(Tools_Library::safeClone( $param ), true );
                }
                break;

            case 'string':
                $content = '"' . addslashes( $param ) . '"';
                break;

            case 'boolean':
                $content = $param ? 'TRUE' : 'FALSE';
                break;

            case 'null':
                $content = 'NULL';
                break;

            case 'resource':
                $content = '[R#' . get_resource_type( $param ) . ']';
                break;

            case 'integer':
            case 'double':
                $content = $param;
                break;

            default:
                $content = '[Unknown type : ' . gettype( $param ) . ']';
        }

        // Manage title
        if ( $title )
            $content = "$title : " . $content;

        // Note: Using a string here to prevent loss of precision
        // in case of "overflow" (PHP converts it to a double)
        $comps = explode(' ', microtime());
        $micro = sprintf('%06d', $comps[0] * 1000000);

        // Add date
        $content = '[' . date('H:i:s') . ":$micro] " . $content;

        return $content . static::getEOL($isWeb);
    }

    // Show log in the browser
    static public function show( $param = null, $title = null ) {

        // Launch the big thing
        echo static::coreLog( $param, $title, true /* IsWeb */ );
    }

    // Log data in file
    static public function trace( $param = null, $title = null ) {

        // Launch the big thing
        file_put_contents('dump.txt', static::coreLog($param, $title), FILE_APPEND);
    }
}
