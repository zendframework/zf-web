<?php

// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV')
        ? getenv('APPLICATION_ENV') : 'production'));


// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(__DIR__ . '/../app'));

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', realpath(__DIR__ . '/..'));
}

// Application.ini.inc cache file
defined('CONFIG_INC')
        || define('CONFIG_INC',
        APPLICATION_ROOT . '/data/cache/' . APPLICATION_ENV . '.ini.inc');

// We use default config if no cache
$configFile = CONFIG_INC;
$noConfigCache = false;
if ( false === is_file(CONFIG_INC) ) {
    $configFile = APPLICATION_PATH . '/config/application.ini';
    $noConfigCache = true;
}

require_once APPLICATION_PATH . '/global.php';

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Zend/Loader/Autoloader.php';


//Zend_Session::writeClose(true); //If you will have problems with sesssions and APC

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, $configFile
);

// Create the cache of config if no
// Only for production

if ($noConfigCache and ('production' == APPLICATION_ENV)) {
    $configs = '<?php' . PHP_EOL
            . 'return '
            . var_export($application->getOptions(), true) . PHP_EOL
            . '?>';
    file_put_contents(CONFIG_INC, $configs);
}

$application->bootstrap()->run();