<?php
class Application_Setup
{
    /**
     * Application instance
     * @var App_Application
     */
    protected static $_application;

    /**
     * Set up and get the Zend_Application instance
     *
     * @return App_Application
     */
    public static function app()
    {
        if(self::$_application === null) {
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

            /** Setup */
            if($config->setup->enabled == true) {
                require_once 'InfoScreen/Setup.php';
                $setup = new InfoScreen_Setup($config);
                $setup->run();
            }

            /** Application */
            require_once 'Zend/Application.php';
            require_once 'InfoScreen/Application.php';

            self::$_application = new InfoScreen_Application(
                APPLICATION_ENV,
                $config
            );
        }

        return self::$_application;
    }
}