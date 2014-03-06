<?php
/**
 * Description: unique entry point for the whole website (hopefully)
 * 
 */
// TBD load globals from a txt file (manage environment?)

// TBD: SITE_ROOT is the webserver (for redirection)
define( 'SITE_ROOT' , 'http://localhost/custom-mvc-framework/?' );

/* Options in case a page is not found
 *      redirect: redirect user to start/main/previous (?)
 *      display : display the dreaded '404 Page not found'
 */
define('PAGE_NOT_FOUND', 'redirect');

// DB access
define('DB_ADDRESS',  '127.0.0.1');
define('DB_USER',     'root');
define('DB_PASSWORD', 'B1Kou2;Tc');
define('DB_NAME',     'myFramework');

define('ALL_EOL', "</br>\n");
define('WEB_EOL', "</br>");

// Fetch the router
require_once( 'libraries/router.php' );
