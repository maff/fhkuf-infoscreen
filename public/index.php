<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Autoloader */
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

/** Config */
$defaultConfigFile = APPLICATION_PATH . '/configs/application.default.ini';
$appConfigFile =     APPLICATION_PATH . '/configs/application.ini';

$config = new Zend_Config_Ini(
        $defaultConfigFile,
        APPLICATION_ENV,
        array('allowModifications' => true)
);

if(file_exists($appConfigFile)) {
    $appConfig = new Zend_Config_Ini($appConfigFile, APPLICATION_ENV);
    $config = $config->merge($appConfig);
}

/** Application */
require_once 'Zend/Application.php';
require_once 'InfoScreen/Application.php';

$application = new InfoScreen_Application(
    APPLICATION_ENV,
    $config
);

$application->bootstrap()
            ->run();