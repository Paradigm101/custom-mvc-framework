<?php

// SITE_ROOT is the webserver for redirection
define( 'SITE_ROOT' , 'http://localhost/<your_website_name>/?' );      // Customize

/* Options in case a page is not found
 *      redirect: redirect user to start/main/previous (?)
 *      display : display the dreaded '404 Page not found'
 */
define('PAGE_NOT_FOUND', 'error');

// Log file, for trace
define('LOG_FILE', 'dump.txt');

// DB access
define('DB_TYPE',     'mysql');
define('DB_ADDRESS',  '127.0.0.1');
define('DB_USER',     'XXX');                   // Customize
define('DB_PASSWORD', 'XXX');                   // Customize
define('DB_NAME',     'XXX');                   // Customize

// For front display
define('ALL_EOL', "<br>\n");
define('WEB_EOL', "<br>");
define('LOREM', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed 
    do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
    ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
    cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id
    est laborum.');
