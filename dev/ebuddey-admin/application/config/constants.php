<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

defined('API_ACCESS_TOKEN')    OR define('API_ACCESS_TOKEN', 'bWlkYXNjb21fYWRtaW46ZGVtbzEw'); 

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*if ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1' ) {
    $path 		= $_SERVER['DOCUMENT_ROOT'] . '/ebuddey-admin/uploads/';
    $imagepath 	= 'http://18.216.41.0/ebuddey-admin/uploads/';
    define( "UPLOADPATH", $path );
    define( "IMAGEPATH", $imagepath );
} else {
    $path = 'http://18.216.41.0/ebuddey-admin/uploads/';
    define( "UPLOADPATH", $path );
}*/

defined('SUCCESS_CODE')      	OR define('SUCCESS_CODE', 111);
defined('ERROR_CODE')      		OR define('ERROR_CODE', 222);

//define('GUIDER_SERVER_KEY', 'AAAACfTuxzI:APA91bHzT-Sj4tMYVhkEP5a-s0YK64OPpZ9XgWWrDiThmm62EWBpXPBquvvWS5dUYvCFrVpyYLagAXJSyzUuidIZisZs-nCjeZA5vtjwXVQzChbSLCY15GHOWhlT2aunuJR8r8ZLqrUy');
//define('TRAVELLER_SERVER_KEY', 'AAAAPEdXlKo:APA91bE1xIblbEk1uJjjgq7CyNartS8R354tx7OFl4YRz6IKK_0ELRRMqQsz_X9XWMSqUbGHjpOQ43Ntj31A62N3qzsnDWNku5VLpeJFoEqj3X2t36C-NpEps8IitGMSp4bE2RQvqqWG');
define('GUIDER_SERVER_KEY', 'AAAAEfeAtZ0:APA91bH8PWykgdKQpGgv0wvWC8hNpKy7InI8huWRxoqtF_RrBTz6UgVzvDf4zwwyeCtOt0vShG2so4QaDTmdyyaSZVWVtIwzxMS8K963riygieadmxXl_LkWMmCYh09r8hXmo5zEHPDp');
define('TRAVELLER_SERVER_KEY', 'AAAA6VaPBAc:APA91bFkco-k7Pajnqc0MLBRkSlWkvvb8ib5usvUk5Qu268B940TghWkL_YGeyRwbIIINBzLVvySL5x7qRxFKI-vp3JE0J2C7pVP1m5zryRBIGUjV6zM7roht48mC-9HFt2ELorHvjLd');
define('GUEST_SERVER_KEY', 'AAAAdXhw9n8:APA91bHuyYTv0z5hu_6FRAEUuqS6ohMsetiXKTmmfgWjwRXv3dW7T2I15y6DjLQYDXyl0TSJ4AEz4bwP8Cuxja_33trduhEjvct8WoHFi7hN8QVGYcCZVeqiTiSuetR7xHT6ksg9cStM');
define('HOST_SERVER_KEY', 'AAAAEfeAtZ0:APA91bH8PWykgdKQpGgv0wvWC8hNpKy7InI8huWRxoqtF_RrBTz6UgVzvDf4zwwyeCtOt0vShG2so4QaDTmdyyaSZVWVtIwzxMS8K963riygieadmxXl_LkWMmCYh09r8hXmo5zEHPDp');
define('PROCESSING_FEE_ENABLED', 'NO');
defined('PROCESSING_FEE')   OR define('PROCESSING_FEE', 5);

define('SENANGPAY_SECRETKEY', '9700-518');
define('SENANGPAY_MERCHANTID', '700152830620181');

define('MERCHANTCODE', 'M10845');
define('MERCHANTKEY', 'SbDGYIUkGL');
define('COUNTRY', 'MY');
define('LOCATION', 'ISO-8859-1');
//define('BACKENDPOSTURL', 'https://www.mobile88.com/epayment/report/');
define('BACKENDPOSTURL', 'http://www.buddeybnb.com/ebuddey-admin/index.php/api/Ipay88backendpost/');
define('CURRENCYCODE', 'RM');
define('COMPRESS_IMG_SIZE', 20);
define('COMPRESS_IDIMG_SIZE', 30);

define('MIN_RATE', 10);
define('MAX_RATE', 5000);

define('DEFAULT_FIXED', 10);
define('DEFAULT_PERCENTAGE', 10);
define('PROCESSING_FEES_VALUE', 5);

define('HOST_NAME', 'Talent');
define('GUEST_NAME', 'Fans');