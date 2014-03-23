<?php

/**
 * class Curl
 *
 * class use for sending curl messages
 *      - First set the option with Curl::setXXX( ... );
 *      - Then send Curl::send();
 *              This is returning a result from the curl execution
 *      - Error can be accessed through Curl::getLastError();
 *      - After the send, every options are reset unless stated otherwise
 *
 * Public Methods:
 *      setHeaders      - set http headers for 'send'
 *      setCredentials  - set user & password for 'send'
 *      setTimeout      - set timeout for 'send'
 *      setReturnAll    - set the option to return all data from non-curl messages for 'send'
 * 
 *      send - send curl message (return status)
 *          set options with previous methods
 * 
 *      getLastError - get error from the last curl send
 *          return empty string if no error
 */
abstract class Curl_Library_Controller {

    // Human readable curl errors
    static private $curlErrorCodes = array(  1 => 'CURLE_UNSUPPORTED_PROTOCOL',
                                             2 => 'CURLE_FAILED_INIT',
                                             3 => 'CURLE_URL_MALFORMAT',
                                             4 => 'CURLE_URL_MALFORMAT_USER',
                                             5 => 'CURLE_COULDNT_RESOLVE_PROXY',
                                             6 => 'CURLE_COULDNT_RESOLVE_HOST',
                                             7 => 'CURLE_COULDNT_CONNECT',
                                             8 => 'CURLE_FTP_WEIRD_SERVER_REPLY',
                                             9 => 'CURLE_REMOTE_ACCESS_DENIED',
                                            11 => 'CURLE_FTP_WEIRD_PASS_REPLY',
                                            13 => 'CURLE_FTP_WEIRD_PASV_REPLY',
                                            14 => 'CURLE_FTP_WEIRD_227_FORMAT',
                                            15 => 'CURLE_FTP_CANT_GET_HOST',
                                            17 => 'CURLE_FTP_COULDNT_SET_TYPE',
                                            18 => 'CURLE_PARTIAL_FILE',
                                            19 => 'CURLE_FTP_COULDNT_RETR_FILE',
                                            21 => 'CURLE_QUOTE_ERROR',
                                            22 => 'CURLE_HTTP_RETURNED_ERROR',
                                            23 => 'CURLE_WRITE_ERROR',
                                            25 => 'CURLE_UPLOAD_FAILED',
                                            26 => 'CURLE_READ_ERROR',
                                            27 => 'CURLE_OUT_OF_MEMORY',
                                            28 => 'CURLE_OPERATION_TIMEDOUT',
                                            30 => 'CURLE_FTP_PORT_FAILED',
                                            31 => 'CURLE_FTP_COULDNT_USE_REST',
                                            33 => 'CURLE_RANGE_ERROR',
                                            34 => 'CURLE_HTTP_POST_ERROR',
                                            35 => 'CURLE_SSL_CONNECT_ERROR',
                                            36 => 'CURLE_BAD_DOWNLOAD_RESUME',
                                            37 => 'CURLE_FILE_COULDNT_READ_FILE',
                                            38 => 'CURLE_LDAP_CANNOT_BIND',
                                            39 => 'CURLE_LDAP_SEARCH_FAILED',
                                            41 => 'CURLE_FUNCTION_NOT_FOUND',
                                            42 => 'CURLE_ABORTED_BY_CALLBACK',
                                            43 => 'CURLE_BAD_FUNCTION_ARGUMENT',
                                            45 => 'CURLE_INTERFACE_FAILED',
                                            47 => 'CURLE_TOO_MANY_REDIRECTS',
                                            48 => 'CURLE_UNKNOWN_TELNET_OPTION',
                                            49 => 'CURLE_TELNET_OPTION_SYNTAX',
                                            51 => 'CURLE_PEER_FAILED_VERIFICATION',
                                            52 => 'CURLE_GOT_NOTHING',
                                            53 => 'CURLE_SSL_ENGINE_NOTFOUND',
                                            54 => 'CURLE_SSL_ENGINE_SETFAILED',
                                            55 => 'CURLE_SEND_ERROR',
                                            56 => 'CURLE_RECV_ERROR',
                                            58 => 'CURLE_SSL_CERTPROBLEM',
                                            59 => 'CURLE_SSL_CIPHER',
                                            60 => 'CURLE_SSL_CACERT',
                                            61 => 'CURLE_BAD_CONTENT_ENCODING',
                                            62 => 'CURLE_LDAP_INVALID_URL',
                                            63 => 'CURLE_FILESIZE_EXCEEDED',
                                            64 => 'CURLE_USE_SSL_FAILED',
                                            65 => 'CURLE_SEND_FAIL_REWIND',
                                            66 => 'CURLE_SSL_ENGINE_INITFAILED',
                                            67 => 'CURLE_LOGIN_DENIED',
                                            68 => 'CURLE_TFTP_NOTFOUND',
                                            69 => 'CURLE_TFTP_PERM',
                                            70 => 'CURLE_REMOTE_DISK_FULL',
                                            71 => 'CURLE_TFTP_ILLEGAL',
                                            72 => 'CURLE_TFTP_UNKNOWNID',
                                            73 => 'CURLE_REMOTE_FILE_EXISTS',
                                            74 => 'CURLE_TFTP_NOSUCHUSER',
                                            75 => 'CURLE_CONV_FAILED',
                                            76 => 'CURLE_CONV_REQD',
                                            77 => 'CURLE_SSL_CACERT_BADFILE',
                                            78 => 'CURLE_REMOTE_FILE_NOT_FOUND',
                                            79 => 'CURLE_SSH',
                                            80 => 'CURLE_SSL_SHUTDOWN_FAILED',
                                            81 => 'CURLE_AGAIN',
                                            82 => 'CURLE_SSL_CRL_BADFILE',
                                            83 => 'CURLE_SSL_ISSUER_ERROR',
                                            84 => 'CURLE_FTP_PRET_FAILED',
                                            84 => 'CURLE_FTP_PRET_FAILED',
                                            85 => 'CURLE_RTSP_CSEQ_ERROR',
                                            86 => 'CURLE_RTSP_SESSION_ERROR',
                                            87 => 'CURLE_FTP_BAD_FILE_LIST',
                                            88 => 'CURLE_CHUNK_FAILED' );

    // Error number from curl API
    private static $curlErrorNumber = null;

    // Curl options
    private static $user     = null;
    private static $password = null;
    private static $headers  = null;
    private static $timeout  = null;

    // Choose between returning all data or just the first one
    //  in case of no curl_init
    private static $returnAll = false;

    // Set credential informations: user/password
    public static function setCredentials( $user, $password ) {
        static::$user     = $user;
        static::$password = $password;
    }

    // Set http headers (array)
    public static function setHeaders( $headers ) {
        static::$headers = $headers;
    }

    // Set timeout (int)
    public static function setTimeout( $timeout ) {
        static::$timeout = $timeout;
    }

    // Set the 'return all' flag (boolean)
    public static function setReturnAll( $isAll ) {
        static::$returnAll = $isAll;
    }

    /**
     * Send a curl post
     * 
     *      send a curl message
     *      store error status
     *      return result from curl exec
     * 
     * @param string  $url         - URL
     * @param string  $post_values - POST values
     * 
     * @return string               - returned result
     */
    public static function send( $url, $post_values, $resetOptions = true ) {

        // We can use curl, yeah!
        if ( function_exists( 'curl_init' ) ) {
            
            // Initialise and set URL
            $connection = curl_init( $url );

            // Set POST variables
            curl_setopt( $connection, CURLOPT_POSTFIELDS, $post_values );

            // Set timeout
            if ( static::$timeout !== null )
                curl_setopt( $connection, CURLOPT_TIMEOUT, static::$timeout );

            // Set user and password
            if ( static::$user !== null )
                curl_setopt( $connection, CURLOPT_USERPWD, static::$user . ':' . static::$password );

            // Set headers
            if ( static::$headers !== null ) {
                curl_setopt( $connection, CURLOPT_HTTPHEADER, $headers );
            }

            // Set to TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
            curl_setopt( $connection, CURLOPT_RETURNTRANSFER, 1 );

            // do not verify certificate
            if ( has_feature('TRAINING_HACKS') ) {
                curl_setopt( $connection, CURLOPT_SSL_VERIFYPEER, 0 );
            }

            // Doing the stuff and storing the result
            $result = curl_exec( $connection );

            // Store error number
            // because after, connection will be closed and it will be too late
            static::$curlErrorNumber = curl_errno( $connection );

            // Closing connection
            curl_close( $connection );
        }
        else {
            $args = '';

            // Timeout
            if ( static::$timeout !== null )
                $args .= '--connect-timeout ' . static::$timeout;

            // allow insecure/training/self signed certificates
            if ( has_feature('TRAINING_HACKS') )
                $args .= ' --insecure ';

            exec( CLIENT_CURL_PATH . ' '
                . $args . ' '
                . '-d "' . $post_values . '" '
                . $url,
                  $result );

            // Choise between returning all or just the first item
            if ( static::$returnAll )
                $result = implode( '', $result );
            else
                $result = $result[0];
        }

        // Work is done, reseting all options
        if ( $resetOptions ) {
            static::$user      = null;
            static::$password  = null;
            static::$headers   = null;
            static::$timeout   = null;
            static::$returnAll = false;
        }

        return( $result );
    }

    // Return curl error from last curl message as a string
    //      empty string if no error
    public static function getLastError() {

        // No error (0) or nothing set yet (null)
        if ( empty( static::$curlErrorNumber ) )
            return '';

        // Error found
        if ( array_key_exists( static::$curlErrorNumber, static::$curlErrorCodes ) )
            return static::$curlErrorCodes[ static::$curlErrorNumber ];

        // Unknown error found
        return 'CURL_UNKNOWN_ERROR : [' . static::$curlErrorNumber . ']';
    }
}
