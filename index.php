<?php
/**
 * index.php
 *
 * @name    index.php
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
/**
 * Index.php dispatcher script
 *
 * @author  Djordje Mandaric <djordje2004@gmail.com>
 */
define('BASE_PATH', realpath(dirname(__FILE__) . '/'));
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));
define('CONFIGURATION_PATH', APPLICATION_PATH . '/configurations');
define('LIBRARY_PATH', BASE_PATH . '/library');

define('APP_ENVIRONMENT', preg_match('/resume.com/', $_SERVER['SERVER_NAME']) ? 'production' : 'development');

/* set the timezone */
date_default_timezone_set('America/Los_Angeles');

/* get autoloader */
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARY_PATH);
require_once LIBRARY_PATH . '/Zend/Loader/Autoloader.php';

/* setup autoloading for desired classes */
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

// Zend_Application
require_once 'Zend/Application.php';

$application = new Zend_Application(APP_ENVIRONMENT, APPLICATION_PATH . '/configurations/application.ini');
$application->bootstrap();
$application->run();