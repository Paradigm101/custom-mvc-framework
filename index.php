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
define('PAGE_NOT_FOUND', 'display');

// DB access
define('DB_ADDRESS',  '127.0.0.1');
define('DB_USER',     'root');
define('DB_PASSWORD', 'B1Kou2;Tc');
define('DB_NAME',     'myFramework');

define('ALL_EOL', "<br>\n");
define('WEB_EOL', "<br>");

define('LOREM', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed 
    do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
    ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
    cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id
    est laborum.');

// Fetch the router
require_once( 'router.php' );
