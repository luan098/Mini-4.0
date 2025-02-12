<?php

/**
 * Configuration
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 *
 * 
 * Configuration for: URL
 * Here we auto-detect your applications URL and the potential sub-folder. Works perfectly on most servers and in local
 * development environments (like WAMP, MAMP, etc.). Don't touch this unless you know what you do.
 *
 * URL_PUBLIC_FOLDER:
 * The folder that is visible to public, users will only have access to that folder so nobody can have a look into
 * "/application" or other folder inside your application or call any other .php file than index.php inside "/public".
 *
 * URL_PROTOCOL:
 * The protocol. Don't change unless you know exactly what you do. This defines the protocol part of the URL, in older
 * versions of MINI it was 'http://' for normal HTTP and 'https://' if you have a HTTPS site for sure. Now the
 * protocol-independent '//' is used, which auto-recognized the protocol.
 *
 * URL_DOMAIN:
 * The domain. Don't change unless you know exactly what you do.
 * If your project runs with http and https, change to '//'
 *
 * URL_SUB_FOLDER:
 * The sub-folder. Leave it like it is, even if you don't use a sub-folder (then this will be just "/").
 *
 * URL:
 * The final, auto-detected URL (build via the segments above). If you don't want to use auto-detection,
 * then replace this line with full URL (and sub-folder) and a trailing slash.
 */

define('TOKEN', '8+d*) 22@o4& 9k95a8 1éã');
define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', '//');
define('URL_DOMAIN', $_SERVER['HTTP_HOST'] ?? '');
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);
ini_set('ignore_repeated_errors', true);
error_reporting(E_ALL);
ini_set("display_errors", true);

define('APP_NAME', 'Mini 4.0');

define('LOCALHOST', 'localhost'); // For localhost development
define('DEVELOPMENT', 'development'); // For a mock/test server
define('PRODUCTION', 'production'); // For the oficial site


/** DES-COMENTE NO AMBIENTE DESEJADO PARA RODAR O SISTEMA */
define('ENVIRONMENT', LOCALHOST);
// define('ENVIRONMENT', DEVELOPMENT);
// define('ENVIRONMENT', PRODUCTION);

define("DB_TYPE", 'mysql');
define('DB_CHARSET', 'utf8');
define('DB_PORT', '3306');
if (ENVIRONMENT === LOCALHOST) {
    define("DB_HOST", "localhost");
    define("DB_NAME", 'mini4');
    define("DB_USER", 'root');
    define("DB_PASS", '');
    define("DEVELOPER_MAILS", []);
} else if (ENVIRONMENT === DEVELOPMENT) {
    define("DB_HOST", '');
    define("DB_NAME", '');
    define("DB_USER", '');
    define("DB_PASS", '');
    define("DEVELOPER_MAILS", []);
} else if (ENVIRONMENT === PRODUCTION) {
    define("DB_HOST", '');
    define("DB_NAME", '');
    define("DB_USER", '');
    define("DB_PASS", '');
    define("DEVELOPER_MAILS", []);
}
