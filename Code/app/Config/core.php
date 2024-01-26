<?php
//echo "<pre>"; print_r($_SERVER);
ob_start();
session_start();

/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * CakePHP Debug Level:
 *
 * Production Mode:
 * 	0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 	1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 	2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */

Configure::write('debug', 0);
//  define('CURRENCY', '$');
//  define('CURR', 'USD');
App::uses('Inflector', 'Utility');
App::uses('ClassRegistry', 'Utility');
if (!defined('CURRENCY')) {
    $CurrencyInfo = ClassRegistry::init('Currency')->find('first', array('conditions' => array('Currency.is_default' => '1')));
    if ($CurrencyInfo) {
        define('CURRENCY', $CurrencyInfo['Currency']['symbol'], true);
        define('CURR', $CurrencyInfo['Currency']['code'], true);
    } else {
        define('CURRENCY', '$');
        define('CURR', 'USD');
    }
} else {
    define('CURRENCY', '$');
    define('CURR', 'USD');
}
/**
 * Configure the Error handler used to handle errors for your application. By default
 * ErrorHandler::handleError() is used. It will display errors using Debugger, when debug > 0
 * and log errors with CakeLog when debug = 0.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle errors. You can set this to any callable type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `level` - integer - The level of errors you are interested in capturing.
 * - `trace` - boolean - Include stack traces for errors in log files.
 *
 * @see ErrorHandler for more information on error handling and configuration.
 */
Configure::write('Error', array(
    'handler' => 'ErrorHandler::handleError',
    'level' => E_ALL & ~E_STRICT & ~E_DEPRECATED,
    'trace' => true
));

/**
 * Configure the Exception handler used for uncaught exceptions. By default,
 * ErrorHandler::handleException() is used. It will display a HTML page for the exception, and
 * while debug > 0, framework errors like Missing Controller will be displayed. When debug = 0,
 * framework errors will be coerced into generic HTTP errors.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle exceptions. You can set this to any callback type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `renderer` - string - The class responsible for rendering uncaught exceptions. If you choose a custom class you
 *   should place the file for that class in app/Lib/Error. This class needs to implement a render method.
 * - `log` - boolean - Should Exceptions be logged?
 * - `skipLog` - array - list of exceptions to skip for logging. Exceptions that
 *   extend one of the listed exceptions will also be skipped for logging.
 *   Example: `'skipLog' => array('NotFoundException', 'UnauthorizedException')`
 *
 * @see ErrorHandler for more information on exception handling and configuration.
 */
Configure::write('Exception', array(
    'handler' => 'ErrorHandler::handleException',
    'renderer' => 'ExceptionRenderer',
    'log' => true
));

/**
 * Application wide charset encoding
 */
Configure::write('App.encoding', 'UTF-8');

/**
 * To configure CakePHP *not* to use mod_rewrite and to
 * use CakePHP pretty URLs, remove these .htaccess
 * files:
 *
 * /.htaccess
 * /app/.htaccess
 * /app/webroot/.htaccess
 *
 * And uncomment the App.baseUrl below. But keep in mind
 * that plugin assets such as images, CSS and JavaScript files
 * will not work without URL rewriting!
 * To work around this issue you should either symlink or copy
 * the plugin assets into you app's webroot directory. This is
 * recommended even when you are using mod_rewrite. Handling static
 * assets through the Dispatcher is incredibly inefficient and
 * included primarily as a development convenience - and
 * thus not recommended for production applications.
 */
//Configure::write('App.baseUrl', env('SCRIPT_NAME'));

/**
 * To configure CakePHP to use a particular domain URL
 * for any URL generation inside the application, set the following
 * configuration variable to the http(s) address to your domain. This
 * will override the automatic detection of full base URL and can be
 * useful when generating links from the CLI (e.g. sending emails)
 */
//Configure::write('App.fullBaseUrl', 'http://example.com');

/**
 * Web path to the public images directory under webroot.
 * If not set defaults to 'img/'
 */
//Configure::write('App.imageBaseUrl', 'img/');

/**
 * Web path to the CSS files directory under webroot.
 * If not set defaults to 'css/'
 */
//Configure::write('App.cssBaseUrl', 'css/');

/**
 * Web path to the js files directory under webroot.
 * If not set defaults to 'js/'
 */
//Configure::write('App.jsBaseUrl', 'js/');

/**
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 * 	Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 * 	`admin_index()` and `/admin/controller/index`
 * 	`manager_index()` and `/manager/controller/index`
 *
 */
Configure::write('Routing.prefixes', array('admin', 'apps'));

/**
 * Turn off all caching application-wide.
 *
 */
//Configure::write('Cache.disable', true);

/**
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * public $cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting public $cacheAction = true,
 * or in each action using $this->cacheAction = true.
 *
 */
//Configure::write('Cache.check', true);

/**
 * Enable cache view prefixes.
 *
 * If set it will be prepended to the cache name for view file caching. This is
 * helpful if you deploy the same application via multiple subdomains and languages,
 * for instance. Each version can then have its own view cache namespace.
 * Note: The final cache file name will then be `prefix_cachefilename`.
 */
//Configure::write('Cache.viewPrefix', 'prefix');

/**
 * Session configuration.
 *
 * Contains an array of settings to use for session configuration. The defaults key is
 * used to define a default preset to use for sessions, any settings declared here will override
 * the settings of the default config.
 *
 * ## Options
 *
 * - `Session.cookie` - The name of the cookie to use. Defaults to 'CAKEPHP'
 * - `Session.timeout` - The number of minutes you want sessions to live for. This timeout is handled by CakePHP
 * - `Session.cookieTimeout` - The number of minutes you want session cookies to live for.
 * - `Session.checkAgent` - Do you want the user agent to be checked when starting sessions? You might want to set the
 *    value to false, when dealing with older versions of IE, Chrome Frame or certain web-browsing devices and AJAX
 * - `Session.defaults` - The default configuration set to use as a basis for your session.
 *    There are four builtins: php, cake, cache, database.
 * - `Session.handler` - Can be used to enable a custom session handler. Expects an array of callables,
 *    that can be used with `session_save_handler`. Using this option will automatically add `session.save_handler`
 *    to the ini array.
 * - `Session.autoRegenerate` - Enabling this setting, turns on automatic renewal of sessions, and
 *    sessionids that change frequently. See CakeSession::$requestCountdown.
 * - `Session.ini` - An associative array of additional ini values to set.
 *
 * The built in defaults are:
 *
 * - 'php' - Uses settings defined in your php.ini.
 * - 'cake' - Saves session files in CakePHP's /tmp directory.
 * - 'database' - Uses CakePHP's database sessions.
 * - 'cache' - Use the Cache class to save sessions.
 *
 * To define a custom session handler, save it at /app/Model/Datasource/Session/<name>.php.
 * Make sure the class implements `CakeSessionHandlerInterface` and set Session.handler to <name>
 *
 * To use database sessions, run the app/Config/Schema/sessions.php schema using
 * the cake shell command: cake schema create Sessions
 *
 */
Configure::write('Session', array(
    'defaults' => 'php'
));

/**
 * A random string used in security hashing methods.
 */
Configure::write('Security.salt', 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
Configure::write('Security.cipherSeed', '76859309657453542496749683645');

/**
 * Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a query string parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps when debug > 0. Set to 'force' to always enable
 * timestamping regardless of debug value.
 */
//Configure::write('Asset.timestamp', true);

/**
 * Compress CSS output by removing comments, whitespace, repeating tags, etc.
 * This requires a/var/cache directory to be writable by the web server for caching.
 * and /vendors/csspp/csspp.php
 *
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use HtmlHelper::css().
 */
//Configure::write('Asset.filter.css', 'css.php');

/**
 * Plug in your own custom JavaScript compressor by dropping a script in your webroot to handle the
 * output, and setting the config below to the name of the script.
 *
 * To use, prefix your JavaScript link URLs with '/cjs/' instead of '/js/' or use JsHelper::link().
 */
//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

/**
 * The class name and database used in CakePHP's
 * access control lists.
 */
Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');

/**
 * Uncomment this line and correct your server timezone to fix
 * any date & time related errors.
 */
date_default_timezone_set('UTC');

/**
 * `Config.timezone` is available in which you can set users' timezone string.
 * If a method of CakeTime class is called with $timezone parameter as null and `Config.timezone` is set,
 * then the value of `Config.timezone` will be used. This feature allows you to set users' timezone just
 * once instead of passing it each time in function calls.
 */
//Configure::write('Config.timezone', 'Europe/Paris');

/**
 *
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'File', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, //[optional]
 * 		'mask' => 0664, //[optional]
 * 	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Apc', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Xcache', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 * 		'user' => 'user', //user from xcache.admin.user settings
 * 		'password' => 'password', //plaintext password (xcache.admin.pass)
 * 	));
 *
 * Memcached (http://www.danga.com/memcached/)
 *
 * Uses the memcached extension. See http://php.net/memcached
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Memcached', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => 'my_connection', // [optional] The name of the persistent connection.
 * 		'compress' => false, // [optional] compress data in Memcached (slower, but uses less memory)
 * 	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 * 		'engine' => 'Wincache', //[required]
 * 		'duration' => 3600, //[optional]
 * 		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 	));
 */
/**
 * Configure the cache handlers that CakePHP will use for internal
 * metadata like class maps, and model schema.
 *
 * By default File is used, but for improved performance you should use APC.
 *
 * Note: 'default' and other application caches should be configured in app/Config/bootstrap.php.
 *       Please check the comments in bootstrap.php for more info on the cache engines available
 *       and their settings.
 */
$engine = 'File';

// In development mode, caches should expire quickly.
$duration = '+999 days';
if (Configure::read('debug') > 0) {
    $duration = '+10 seconds';
}

// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.
$prefix = 'myapp_';

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
    'engine' => $engine,
    'prefix' => $prefix . 'cake_core_',
    'path' => CACHE . 'persistent' . DS,
    'serialize' => ($engine === 'File'),
    'duration' => $duration
));

/**
 * Configure the cache for model and datasource caches. This cache configuration
 * is used to store schema descriptions, and table listings in connections.
 */
Cache::config('_cake_model_', array(
    'engine' => $engine,
    'prefix' => $prefix . 'cake_model_',
    'path' => CACHE . 'models' . DS,
    'serialize' => ($engine === 'File'),
    'duration' => $duration
));



//define('SITE_TITLE', 'Job Site Srcipt');
//define('SITE_URL', 'jobsitescript.com');
//define('TAG_LINE', "We value your business. We value your skills");
define('MAIL_FROM', 'info@resumehire.net');
define('FEEDBACK_MAIL', 'info@resumehire.net');
define('ENQUIRY_MAIL', 'info@resumehire.net');
define('PAYMENT_ENQUIRY_MAIL', 'info@resumehire.net');
define('HOME_META_TITLE', 'Resumehire');

//define('TITLE_FOR_PAGES', SITE_TITLE . " :: " . TAG_LINE . " - ");
define('IS_LIVE', '1');
define('HTTP_PATH', 'https://' . $_SERVER['SERVER_NAME'] . '');
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . "");
define('HTTP_IMAGE', HTTP_PATH . '/app/webroot/img');

define('PHP_PATH', HTTP_PATH . '/app/webroot/php/');
define('MAX_IMAGE', '5');
if(!isset($_SESSION['Config']['language'])){
    $_SESSION['Config']['language'] = 'en';
}
//echo $_SESSION['Config']['language'];die; 

/* * ***************************** User Image Path ****************************** */
define('UPLOAD_FULL_PROFILE_IMAGE_PATH', BASE_PATH . '/app/webroot/files/user/full/');

define('UPLOAD_THUMB_PROFILE_IMAGE_PATH', BASE_PATH . '/app/webroot/files/user/thumb/');
define('UPLOAD_SMALL_PROFILE_IMAGE_PATH', BASE_PATH . '/app/webroot/files/user/small/');

define('UPLOAD_FULL_PROFILE_IMAGE_WIDTH', '');
define('UPLOAD_FULL_PROFILE_IMAGE_HEIGHT', '');
define('UPLOAD_THUMB_PROFILE_IMAGE_WIDTH', 200);
define('UPLOAD_THUMB_PROFILE_IMAGE_HEIGHT', '');
define('UPLOAD_SMALL_PROFILE_IMAGE_WIDTH', 80);
define('UPLOAD_SMALL_PROFILE_IMAGE_HEIGHT', '');

define('UPLOAD_FULL_PROFILE_LOGO_IMAGE_WIDTH', 1260);
define('UPLOAD_FULL_PROFILE_LOGO_IMAGE_HEIGHT', 264);

define('DISPLAY_FULL_PROFILE_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/user/full/');
define('DISPLAY_THUMB_PROFILE_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/user/thumb/');
define('DISPLAY_SMALL_PROFILE_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/user/small/');



// Defining images path for Banner Advertisement

define('UPLOAD_FULL_BANNER_AD_IMAGE_PATH', BASE_PATH . '/app/webroot/files/bannerad/full/');
define('UPLOAD_THUMB_BANNER_AD_IMAGE_PATH', BASE_PATH . '/app/webroot/files/bannerad/thumb/');

define('UPLOAD_THUMB_BANNER_AD_IMAGE_WIDTH', 150);
define('UPLOAD_THUMB_BANNER_AD_IMAGE_HEIGHT', '');

define('UPLOAD_FULL_BANNER_AD_IMAGE_WIDTH', '');
define('UPLOAD_FULL_BANNER_AD_IMAGE_HEIGHT', '');

define('DISPLAY_FULL_BANNER_AD_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/bannerad/full/');
define('DISPLAY_THUMB_BANNER_AD_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/bannerad/thumb/');



/* * ***************************** Website Logo Path ****************************** */
define('UPLOAD_FULL_WEBSITE_LOGO_PATH', BASE_PATH . '/app/webroot/files/websitelogo/full/');

define('UPLOAD_THUMB_WEBSITE_LOGO_PATH', BASE_PATH . '/app/webroot/files/websitelogo/thumb/');
define('UPLOAD_SMALL_WEBSITE_LOGO_PATH', BASE_PATH . '/app/webroot/files/websitelogo/small/');

define('UPLOAD_FULL_WEBSITE_LOGO_WIDTH', 285);
define('UPLOAD_FULL_WEBSITE_LOGO_HEIGHT', 50);
define('UPLOAD_THUMB_WEBSITE_LOGO_WIDTH', 200);
define('UPLOAD_THUMB_WEBSITE_LOGO_HEIGHT', '');
define('UPLOAD_SMALL_WEBSITE_LOGO_WIDTH', 80);
define('UPLOAD_SMALL_WEBSITE_LOGO_HEIGHT', '');

define('DISPLAY_FULL_WEBSITE_LOGO_PATH', HTTP_PATH . '/app/webroot/files/websitelogo/full/');
define('DISPLAY_THUMB_WEBSITE_LOGO_PATH', HTTP_PATH . '/app/webroot/files/websitelogo/thumb/');
define('DISPLAY_SMALL_WEBSITE_LOGO_PATH', HTTP_PATH . '/app/webroot/files/websitelogo/small/');

/* * ***************************** Blog Path ****************************** */
define('UPLOAD_FULL_BLOG_PATH', BASE_PATH . '/app/webroot/files/blog/full/');

define('UPLOAD_THUMB_BLOG_PATH', BASE_PATH . '/app/webroot/files/blog/thumb/');
define('UPLOAD_SMALL_BLOG_PATH', BASE_PATH . '/app/webroot/files/blog/small/');

define('UPLOAD_FULL_BLOG_WIDTH', '');
define('UPLOAD_FULL_BLOG_HEIGHT', '');
define('UPLOAD_THUMB_BLOG_WIDTH', 200);
define('UPLOAD_THUMB_BLOG_HEIGHT', '');
define('UPLOAD_SMALL_BLOG_WIDTH', 80);
define('UPLOAD_SMALL_BLOG_HEIGHT', '');

define('DISPLAY_FULL_BLOG_PATH', HTTP_PATH . '/app/webroot/files/blog/full/');
define('DISPLAY_THUMB_BLOG_PATH', HTTP_PATH . '/app/webroot/files/blog/thumb/');
define('DISPLAY_SMALL_BLOG_PATH', HTTP_PATH . '/app/webroot/files/blog/small/');

// Defining images path for slider images

define('UPLOAD_FULL_SLIDER_IMAGE_PATH', BASE_PATH . '/app/webroot/files/slider/full/');
define('UPLOAD_THUMB_SLIDER_IMAGE_PATH', BASE_PATH . '/app/webroot/files/slider/thumb/');

define('UPLOAD_THUMB_SLIDER_IMAGE_WIDTH', 150);
define('UPLOAD_THUMB_SLIDER_IMAGE_HEIGHT', '');

define('UPLOAD_FULL_SLIDER_IMAGE_WIDTH', '');
define('UPLOAD_FULL_SLIDER_IMAGE_HEIGHT', '');

define('DISPLAY_FULL_SLIDER_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/slider/full/');
define('DISPLAY_THUMB_SLIDER_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/slider/thumb/');

/* * ***************************** Job Logo Path ****************************** */
define('UPLOAD_JOB_LOGO_PATH', BASE_PATH . '/app/webroot/files/joblogo/');

define('UPLOAD_JOB_LOGO_WIDTH', 200);
define('UPLOAD_JOB_LOGO_HEIGHT', '');


define('DISPLAY_JOB_LOGO_PATH', HTTP_PATH . '/app/webroot/files/joblogo/');



/* * ***************************** Category Image Path ****************************** */
define('UPLOAD_FULL_CATEGORY_IMAGE_PATH', BASE_PATH . '/app/webroot/files/categoryimages/full/');

define('UPLOAD_THUMB_CATEGORY_IMAGE_PATH', BASE_PATH . '/app/webroot/files/categoryimages/thumb/');
define('UPLOAD_SMALL_CATEGORY_IMAGE_PATH', BASE_PATH . '/app/webroot/files/categoryimages/small/');

define('UPLOAD_FULL_CATEGORY_IMAGE_WIDTH', '');
define('UPLOAD_FULL_CATEGORY_IMAGE_HEIGHT', '');
define('UPLOAD_THUMB_CATEGORY_IMAGE_WIDTH', 200);
define('UPLOAD_THUMB_CATEGORY_IMAGE_HEIGHT', '');
define('UPLOAD_SMALL_CATEGORY_IMAGE_WIDTH', 80);
define('UPLOAD_SMALL_CATEGORY_IMAGE_HEIGHT', '');

define('DISPLAY_FULL_CATEGORY_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/categoryimages/full/');
define('DISPLAY_THUMB_CATEGORY_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/categoryimages/thumb/');
define('DISPLAY_SMALL_CATEGORY_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/categoryimages/small/');



/* * ***************************** favicon Path ****************************** */
define('UPLOAD_FULL_FAV_PATH', BASE_PATH . '/app/webroot/files/favicon/full/');

define('UPLOAD_THUMB_FAV_PATH', BASE_PATH . '/app/webroot/files/favicon/thumb/');
define('UPLOAD_SMALL_FAV_PATH', BASE_PATH . '/app/webroot/files/favicon/small/');

define('UPLOAD_FULL_FAV_WIDTH', '');
define('UPLOAD_FULL_FAV_HEIGHT', '');
define('UPLOAD_THUMB_FAV_WIDTH', 16);
define('UPLOAD_THUMB_FAV_HEIGHT', 16);
define('UPLOAD_SMALL_FAV_WIDTH', 16);
define('UPLOAD_SMALL_FAV_HEIGHT', 16);

define('DISPLAY_FULL_FAV_PATH', HTTP_PATH . '/app/webroot/files/favicon/full/');
define('DISPLAY_THUMB_FAV_PATH', HTTP_PATH . '/app/webroot/files/favicon/thumb/');
define('DISPLAY_SMALL_FAV_PATH', HTTP_PATH . '/app/webroot/files/favicon/small/');

/* * ***************************** resume Path ****************************** */
define('DISPLAY_RESUME_PATH', HTTP_PATH . '/app/webroot/files/resumes/');
define('UPLOAD_RESUME_PATH', BASE_PATH . '/app/webroot/files/resumes/');

/* * ***************************** mail Path ****************************** */
define('DISPLAY_MAIL_PATH', HTTP_PATH . '/app/webroot/files/mail/');
define('UPLOAD_MAIL_PATH', BASE_PATH . '/app/webroot/files/mail/');

define('DISPLAY_VIDEO_PATH', HTTP_PATH . '/app/webroot/files/video/');
define('UPLOAD_VIDEO_PATH', BASE_PATH . '/app/webroot/files/video/');

/* * ***************************** document Path ****************************** */
define('DISPLAY_DOCUMENT_PATH', HTTP_PATH . '/app/webroot/files/document/');
define('UPLOAD_DOCUMENT_PATH', BASE_PATH . '/app/webroot/files/document/');

/* Global Array to validate extentions for image upload */
global $extentions;
$extentions = array(
    'jpg' => 'jpg',
    'jpeg' => 'jpeg',
    'gif' => 'gif',
    'png' => 'png'
);

/* Global Array to validate extentions for video upload */
global $extentions_video;
$extentions_video = array(
    'mp4' => 'mp4',
    '3gp' => '3gp',
    'avi' => 'avi'
);

global $favextentions;
$favextentions = array(
    'ico' => 'ico',
);

/* Global Array to validate extentions for cv upload */
global $extentions_doc;
$extentions_doc = array(
    'doc' => 'doc',
    'docx' => 'docx',
    'pdf' => 'pdf'
);


global $server_file_path;
$server_file_path = array(
    UPLOAD_FULL_PROFILE_IMAGE_PATH,
    UPLOAD_THUMB_PROFILE_IMAGE_PATH,
    UPLOAD_SMALL_PROFILE_IMAGE_PATH
);

global $file_path;
$file_path = array(
    'User.profile_image' => 'UPLOAD_FULL_PROFILE_IMAGE_PATH,UPLOAD_THUMB_PROFILE_IMAGE_PATH,UPLOAD_SMALL_PROFILE_IMAGE_PATH'
);

global $designation;
$designation = array(
    '1' => 'HR',
    '2' => 'Director'
);


global $marital_status_option;
$marital_status_option = array(
    'Single' => 'Single',
    'Married' => 'Married',
    'Divorced' => 'Divorced',
    'Widowed' => 'Widowed',
);


global $language_option;
$language_option = array(
    'English' => 'English',
    'French' => 'French',
    'German' => 'German',
    'Italian' => 'Italian',
);

global $expYear;
$expYearAA = range(0, 30);
$expYear = array_combine($expYearAA, $expYearAA);
global $expMonth;
$expMonthAA = range(0, 11);
$expMonth = array_combine($expMonthAA, $expMonthAA);

define('UPLOAD_MAX_IMAGE', 10);
define('UPLOAD_CERTIFICATE_PATH', BASE_PATH . '/app/webroot/files/certificates/');
define('DISPLAY_CERTIFICATE_PATH', HTTP_PATH . '/app/webroot/files/certificates/');

define('UPLOAD_TMP_CERTIFICATE_PATH', BASE_PATH . '/app/webroot/files/certificates/tmp/');
define('DISPLAY_TMP_CERTIFICATE_PATH', HTTP_PATH . '/app/webroot/files/certificates/tmp/');

define('UPLOAD_CV_PATH', BASE_PATH . '/app/webroot/files/cv/');

//define('CURRENCY', '$');
//define('CURR', 'USD');
define('API_KEY', 'JOB45689ASD9857ASDSCRIPT');


define('PAYPAL_EMAIL', 'pradhan.ashish@logicspice.com');
define('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('BRAINTREE_MERCHANT_ID','s52tpvcxfkqx4j89');
define('BRAINTREE_PUBLIC_KEY','c94s8548ft6rp88w');
define('BRAINTREE_PRIVATE_KEY','d65449d0b50f2d18abbb4b5a9ce26e59');

///////shivani
 define('CAPTCHA_KEY', '6Lf5zjsnAAAAAGnzdS8JCdJ7WO_7I_X29PrZDQGB');
 define('CAPTCHA_SECRET_KEY', '6Lf5zjsnAAAAABq_GrL5vA7huSm-Adc3I_T_-C1d');


global $priceArray;
$price = 15;
$priceArray[1] = CURRENCY . '0' . ' - ' . CURRENCY . '15K';
for ($i = 2; $i < 13; $i++) {
    $sPrice = $price;
    $price = $price + 15;
    $ePrice = $price;
    $priceArray[$i] = CURRENCY . $sPrice . 'K - ' . CURRENCY . $ePrice . 'K';
}
$priceArray[$i] = CURRENCY . $ePrice . 'K - ' . CURRENCY . '200K';


define('CURRENT_TIME', 0);
global $advertisementPlace;
$advertisementPlace = array(
    // 'job_selection' => 'Job Package Selection page (Width:1294px, Height:292px)'
    'home_ad1' => 'Home page Advertisement box1 (Width:377px, height:387px)',
    'home_ad2' => 'Home page Advertisement box2 (Width:377px, height:387px)'
);

global $salary;
$sal = 0;
for ($i = 1; $sal <= 180; $i++) {
    $salary[$i] = CURRENCY . $sal . 'K';
    $sal = $sal + 15;
}

$salary[$i] = CURRENCY . '200' . 'K';

global $minSalary;
$minsal = 50;
for ($i = 50; $minsal <= 1900; $i++) {
    $minSalary[$minsal] = CURRENCY . ' ' . $minsal . 'K';
    if ($minsal < 100) {
        $minsal = $minsal + 10;
    } elseif ($minsal >= 100 && $minsal < 1000) {
        $minsal = $minsal + 25;
    } elseif ($minsal >= 1000 && $minsal < 2000) {
        $minsal = $minsal + 100;
    }
}

$minSalary[$minsal] = CURRENCY . ' ' . '2000' . 'K & above';


define('UPLOAD_FULL_INVOICE_IMAGE_PATH', BASE_PATH . '/app/webroot/files/invoice/');
define('DISPLAY_FULL_INVOICE_IMAGE_PATH', HTTP_PATH . '/app/webroot/files/invoice/');


define('FACEBOOK_APP_ID', '1917640701854957');
define('FACEBOOK_SECRET', '361b8ac055e547abc9cc6eca08afc42f');

/* --Demo app gmail-- */
define('GMAIL_CLIENT_ID', '1019675057385-g6ajeu1e2b1ef2thtr6pmmndoe6du65v.apps.googleusercontent.com');
define('GMAIL_SECRET', '_e3vfdBH8NKld7hV0niQN37h');
define('GMAIL_DEVELOPER_KEY', '1019675057385-g6ajeu1e2b1ef2thtr6pmmndoe6du65v@developer.gserviceaccount.com');
define('GMAILREDIRECT', 'https://job-board-portal-script.logicspice.com/users/gmaillogin');
define("GMAILCLIENT", BASE_PATH . "/app/webroot/gmailsrc/Google_Client.php");
define("GMAILOAUTH", BASE_PATH . "/app/webroot/gmailsrc/contrib/Google_Oauth2Service.php");

// Linkedin  App id and Secret
define('LINKEDIN_API_KEY', '86poiltv60pylg');
define('LINKEDIN_SECRET', 'jnJaEaW9TNXhzFtN');
define('LINKEDIN_OUATH_USER_TOKEN', '81--30db3b0a-4929-4561-826e-b6043de36416');
define('LINKEDIN_OUATH_USER_SECRET', '1825807c-4306-4a09-a499-24a5b43115ed');
//define('REDIRECT_URI', 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);
//define('LINKEDIN_REDIRECT', 'http://demo.imagetowebpage.com/uuuga/users/linkedinlogin');
define('LINKEDIN_REDIRECT', 'https://job-board-portal-script.logicspice.com/users/linkedinlogin');
define("LINKEDIN_HTTP_FILE", BASE_PATH . "/app/webroot/linkedinoauth/http.php");
define("LINKEDIN_OAUTH_CLIENT_FILE", BASE_PATH . "/app/webroot/linkedinoauth/oauth_client.php");

global $sallery;
$sallery = array('0-1000' => '<'.CURRENCY.' 1000', '1000-2000' => CURRENCY.' 1000 To '.CURRENCY.'2000', '2000-3000' => CURRENCY.' 2000 To '.CURRENCY.'3000', '4000-5000' => CURRENCY.' 4000 To '.CURRENCY.'5000', '5000-7000' => CURRENCY.' 5000 To '.CURRENCY.'7000', '7000-10000' => CURRENCY.' 7000 To '.CURRENCY.'10000'
    , '10000-12000' => CURRENCY.' 10000 To '.CURRENCY.'12000', '12000-15000' => CURRENCY.' 12000 To '.CURRENCY.'15000', '15000-20000' => CURRENCY.' 15000 To '.CURRENCY.'20000', '20000-25000' => CURRENCY.' 20000 To '.CURRENCY.'25000', '25000-30000' => CURRENCY.' 25000 To '.CURRENCY.'30000', '30000-1000000' => '>'.CURRENCY.' 30000');

global $salleryMin;
$salleryMin = array('0-1000' => '<'.CURRENCY.' 1000', '1-2' => CURRENCY.' 1000 To '.CURRENCY.'2000', '2-3' => CURRENCY.' 2000 To '.CURRENCY.'3000', '4-5' => CURRENCY.' 4000 To '.CURRENCY.'5000', '5-7' => CURRENCY.' 5000 To '.CURRENCY.'7000', '7-10' => CURRENCY.' 7000 To '.CURRENCY.'10000'
    , '10-12' => CURRENCY.' 10000 To '.CURRENCY.'12000', '12-15' => CURRENCY.' 12000 To '.CURRENCY.'15000', '15-20' => CURRENCY.' 15000 To '.CURRENCY.'20000', '20-25' => CURRENCY.' 20000 To '.CURRENCY.'25000', '25-30' => CURRENCY.' 25000 To '.CURRENCY.'30000', '30-1000' => '>'.CURRENCY.' 30000');

define('SECURE_CODE', 'JobSiteScript');

global $alpha;
$alpha = range("A","Z");
$alpha = array_combine($alpha,$alpha);


if($_SESSION['Config']['language'] == 'de'){
    global $planType;
    $planType = array(
        'Years' => 'JÃ¤hrlich',
        'Months' => 'Monatlich',
    );
    global $planFeatuers;
    $planFeatuers = array(
        '1' => 'Anzahl der Stellenanzeigen',
        '2' => 'Anzahl der Fortsetzungsdownloads',
        '3' => 'Zugangskandidaten Suchfunktion',
        '4' => 'Anzahl der Bewerbungen',
        '5' => 'Anzahl der Aufrufe des Kandidatenprofils',
    );

    global $planFeatuersDis;
    $planFeatuersDis = array(
        '1' => 'Jobpost',
        '2' => 'Download fortsetzen',
        '3' => 'Zugangskandidaten suchen',
        '4' => 'Bewerbung',
         '5' => 'Kandidatenprofilansichten',
    );
    global $planFeatuersHelpText;
    $planFeatuersHelpText = array(
        '1' => 'Sie kÃ¶nnen [!JOBS!] Jobs innerhalb von [!TIME!] Posten',
        '2' => 'Sie kÃ¶nnen [!RESUME!] Resume unter diesem Plan innerhalb von [!TIME!] Herunterladen,',
        '3' => 'Sie kÃ¶nnen auf die Kandidatensuchfunktion zugreifen, um den besten Kandidaten fÃ¼r Ihren Job zu finden',
         '5' => 'Alle Informationen finden Sie im Kandidatenprofil.'
    );

    global $subadminroles;
    $subadminroles =  array(
        "1" => "Arbeitgeber verwalten",
         '2' => 'Arbeitssuchende verwalten',
         '3' => 'Jobs verwalten',
         '4' => 'Kategorien verwalten',
         '5' => 'Blogs verwalten',
         '6' => 'FÃ¤higkeiten verwalten',
         '7' => 'Bezeichnung verwalten',
         '8' => 'Kurse verwalten',
         '9' => 'E-Mail-Vorlagenliste verwalten',
         '10' => 'Seiten verwalten',
         '11' => 'Standorte verwalten',
    );
    
    global $worktype;
    $worktype = array(
         '1' => 'Vollzeit',
         '2' => 'Teilzeit',
         '3' => 'LÃ¤ssig',
         '4' => 'Saisonal',
         '5' => 'fester Begriff',
    );
    global $experienceArray;
    $experienceArray = array(
        '0-1' => 'Weniger als 1 Jahr',
        '1-2' => '1+ bis 2 Jahre',
        '2-5' => '2+ bis 5 Jahre',
        '2-5' => '2+ bis 5 Jahre',
        '5-7' => '5+ bis 7 Jahre',
        '7-10' => '7+ bis 10 Jahre',
        '10 -15 '=> '10 + bis 15 Jahre',
        '15 -35 '=>' Mehr als 15 Jahre ',
    );
    
    global $totalexperienceArray;
    $totalexperienceArray = array(
         '0' => 'Weniger als 1 Jahr',
         '1' => '1 Jahre',
         '2' => '2 Jahre',
         '3' => '3 Jahre',
         '4' => '4 Jahre',
         '5' => '5 Jahre',
         '6' => '6 Jahre',
         '7' => '7 Jahre',
         '8' => '8 Jahre',
         '9' => '9 Jahre',
         '10' => '10 + Jahre',
         '15' => '15 + Jahre ',
    );
    
    global $active_option;
    $active_option = array('short_list' => 'Auswahlliste', 'interview' => 'Interview', 'offer' => 'Angebot', 'accept' => 'Akzeptieren', 'not_suitable' => 'Nicht geeignet');
     global $monthName;
    $monthName = array(
        '1' => 'Januar',
        '2' => 'Februar',
        '3' => 'MÃ¤rz',
        '4' => 'April',
        '5' => 'Kann',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'August',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Dezember',
    );
} else if ($_SESSION['Config']['language'] == 'fra') {
    global $planType;
    $planType = array(
        'Years' => 'JÃ¤hrlich',
        'Months' => 'Monatlich',
    );
     global $planFeatuers;
    $planFeatuers = array(
        '1' => 'Nummer der Stellenanzeige',
        '2' => 'Nummer des Lebenslaufdownloads',
        '3' => 'Zugriff auf die Kandidatensuchfunktion',
        '4' => "Nombre d'emplois postulés",
         '5' => "Nombre de vues du profil du candidat",
    );

    global $planFeatuersDis;
    $planFeatuersDis = array(
        '1' => 'Jobpost',
        '2' => 'Download fortsetzen',
        '3' => 'Zugriff auf Kandidatensuche',
        '4' => 'Postuler à un emploi',
         '5' => 'Vues du profil du candidat',
    );
    global $planFeatuersHelpText;
    $planFeatuersHelpText = array(
        '1' => 'Du kannst posten [!JOBS!] ArbeitsplÃ¤tze innerhalb [!TIME!]',
        '2' => 'Sie kÃ¶nnen herunterladen [!RESUME!] Fortsetzen Sie unter diesem Plan innerhalb [!TIME!]',
        '3' => 'Sie kÃ¶nnen auf die Kandidatensuchfunktion zugreifen, um den besten Kandidaten fÃ¼r Ihren Job zu filtern',
         '5' => 'Vous pouvez consulter le profil du candidat pour toutes les informations.'
    );

    global $subadminroles;
    $subadminroles = array(
        '1' => 'Arbeitgeber verwalten',
        '2' => 'Arbeitsuchende verwalten',
        '3' => 'Jobs verwalten',
        '4' => 'Kategorien verwalten',
        '5' => 'Manage Blogs',
        '6' => 'Manage Skills',
        '7' => 'Manage Designation',
        '8' => 'Manage Courses',
        '9' => 'Manage Email Template List',
        '10' => 'Manage Pages',
        '11' => 'Manage Locations',
    );
    global $worktype;
    $worktype = array(
        '1' => 'Ã€ plein temps',
        '2' => 'Ã€ temps partiel',
        '3' => 'DÃ©contractÃ©',
        '4' => 'Saisonnier',
        '5' => 'DurÃ©e dÃ©terminÃ©e',
    );
    global $experienceArray;
    $experienceArray = array(
        '0-1' => 'Moins de 1 an',
        '1-2' => '1+ Ã  2 ans',
        '2-5' => '2+ Ã  5 ans',
        '2-5' => '2+ Ã  5 ans',
        '5-7' => '5+ Ã  7 ans',
        '7-10' => '7+ Ã  10 ans',
        '10-15' => '10+ Ã  15 ans',
        '15-35' => 'Plus de 15 ans',
    );

    global $totalexperienceArray;
    $totalexperienceArray = array(
        '0' => 'Moins de 1 an',
        '1' => '1 ans',
        '2' => '2 ans',
        '3' => '3 ans',
        '4' => '4 ans',
        '5' => '5 ans',
        '6' => '6 ans',
        '7' => '7 ans',
        '8' => '8 ans',
        '9' => '9 ans',
        '10' => '10+ ans',
        '15' => '15+ ans',
    );
    global $active_option;
    $active_option = array('short_list' => 'Liste restreinte', 'interview' => 'Entretien', 'offer' => 'Offre', 'accept' => 'Acceptez', 'not_suitable' => 'Ne convient pas');
    global $monthName;
$monthName = array(
    '1' => 'Janvier',
    '2' => 'FÃ©vrier',
    '3' => 'Mars',
    '4' => 'Avril',
    '5' => 'Mai',
    '6' => 'Juin',
    '7' => 'Juillet',
    '8' => 'AoÃ»t',
    '9' => 'Septembre',
    '10' => 'Octobre',
    '11' => 'Novembre',
    '12' => 'Decemeber',
);
}else{
    global $planType;
    $planType = array(
        'Years' => 'Yearly',
        'Months' => 'Monthly',
    );
    global $planFeatuers;
    $planFeatuers = array(
        '1' => 'Number of Job Post',
        '2' => 'Number of resume download',
        '3' => 'Access candidate search functionality',
        '4' => 'Number of Job Apply',
        '5' => 'Number of Candidate Profile Views',
    );

    global $planFeatuersDis;
    $planFeatuersDis = array(
        '1' => 'Job Post',
        '2' => 'Resume Download',
        '3' => 'Access Candidate Searching',
        '4' => 'Job Apply',
        '5' => 'Candidates Profile View',
    );
    global $planFeatuersHelpText;
    $planFeatuersHelpText = array(
        '1' => 'You can post [!JOBS!] jobs within [!TIME!]',
        '2' => 'You can download [!RESUME!] Resume under this plan within [!TIME!]',
        '3' => 'You can access candidate search functionality to filter best candidate for your job',
         '5' => 'You can view the candidate profile for all the information.'
    );

    global $subadminroles;
    $subadminroles =  array(
        '1' => 'Manage Employers',
        '2' => 'Manage Jobseekers',
        '3' => 'Manage Jobs',
        '4' => 'Manage Categories',
        '5' => 'Manage Blogs',
        '6' => 'Manage Skills',
        '7' => 'Manage Designation',
        '8' => 'Manage Courses',
        '9' => 'Manage Email Template List',
        '10' => 'Manage Pages',
        '11' => 'Manage Locations',
    );
    global $worktype;
    $worktype = array(
        '1' => 'Full Time',
        '2' => 'Part Time',
        '3' => 'Casual',
        '4' => 'Seasonal',
        '5' => 'Fixed Term',
    );
    global $experienceArray;
    $experienceArray = array(
        '0-1' => 'Less than 1 Year',
        '1-2' => '1+ to 2 Years',
        '2-5' => '2+ to 5 Years',
        '2-5' => '2+ to 5 Years',
        '5-7' => '5+ to 7 Years',
        '7-10' => '7+ to 10 Years',
        '10-15' => '10+ to 15 Years',
        '15-35' => 'More than 15 Years',
    );
    
    global $totalexperienceArray;
    $totalexperienceArray = array(
        '0' => 'Less than 1 Year',
        '1' => '1 Years',
        '2' => '2 Years',
        '3' => '3 Years',
        '4' => '4 Years',
        '5' => '5 Years',
        '6' => '6 Years',
        '7' => '7 Years',
        '8' => '8 Years',
        '9' => '9 Years',
        '10' => '10+ Years',
        '15' => '15+ Years',
    );
    
    global $active_option;
    $active_option = array('short_list' => 'Shortlist', 'interview' => 'Interview', 'offer' => 'Offer', 'accept' => 'Accept', 'not_suitable' => 'Not suitable');
    
global $monthName;
$monthName = array(
    '1' => 'January',
    '2' => 'February',
    '3' => 'March',
    '4' => 'April',
    '5' => 'May',
    '6' => 'June',
    '7' => 'July',
    '8' => 'August',
    '9' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December',
);
}

global $planFeatuersMax;
$planFeatuersMax = array(
    '1' => '50000',
    '2' => '1000000',
    '4' => '1000000'
);

global $subroles;
$subroles =  array(
    '1' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '2' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '3' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '4' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '5' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '6' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '7' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '8' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '9' => array('2'=>'Edit'),
    '10' => array('2'=>'Edit'),
    '11' => array('1'=>'Add', '2'=>'Edit','3'=>'Delete'),
    '12' => array('2'=>'Edit'),
);
//define('AUTO_SUGGESTION', 'AIzaSyAfLv-IdHZm0Xy3kYlAm3TypjjqeUjra9Q'); 
define('AUTO_SUGGESTION', 'AIzaSyBqSu5gm1bTwjgpgFl9O0PT1ZVTYbUN60Q'); 

define('CLIENT_ID', GMAIL_CLIENT_ID);
define('CLIENT_SECRET', GMAIL_SECRET);
define('CLIENT_REDIRECT_URL', GMAILREDIRECT);
?>
