<?php
require_once 'Zend/Loader.php';

class Bootstrap
{
    public static $frontController = null;

    public static function run()
    {
        self::setupEnvironment();
        self::setupRegistry();
        self::setupMVC();
        self::$frontController->dispatch();
    }

    public static function setupEnvironment()
    {
        self::setupErrorReporting(true);
        self::setupLocale();
        self::setupAutoload();
    }

    public static function setupAutoload()
    {
        self::setupDirectories();
        Zend_Loader::registerAutoload();
    }
    
    public static function setupDirectories()
    {
        set_include_path('.' . PATH_SEPARATOR . 'library/'
        . PATH_SEPARATOR . 'application/models'
        . PATH_SEPARATOR . 'application/forms'
        . PATH_SEPARATOR . get_include_path());
    }
    
    public static function setupErrorReporting($reporting = true)
    {
        if($reporting === true)
        {
            error_reporting(E_ALL|E_STRICT);
            ini_set('display_errors', true);
        }
        else
        {
            error_reporting(0);
            ini_set('display_errors', false);
        }
    }
    
    public static function setupLocale()
    {
        date_default_timezone_set('Europe/Vienna');  
    }
    
    public static function setupRegistry()
    {
        $registry = Zend_Registry::getInstance();
        $registry->set('config', self::getConfig());
        $registry->set('base_path', dirname(__FILE__));
    }
    
    public static function getConfig()
    {
        return new Zend_Config_Ini('application/config/config.ini', 'settings');
    }

    public static function setupMVC()
    {
        self::setupLayout();
        self::setupFrontController();
        self::setupRoutes();
        //self::setupView();
    }
    
    public static function setupLayout()
    {
        Zend_Layout::startMvc(array('layoutPath' => 'application/layouts'));
        
        $documentType = new Zend_View_Helper_Doctype();
        $documentType->doctype('XHTML1_STRICT');
    }

    public static function setupFrontController()
    {
        Zend_Loader::registerAutoload();
        self::$frontController = Zend_Controller_Front::getInstance();
        self::$frontController->throwExceptions(true);
        self::$frontController->setControllerDirectory('application/controllers');
        self::$frontController->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
    }
    
    public static function setupRoutes()
    {
        $router = self::$frontController->getRouter();
        $router->addRoute(
            'filter',
            new Zend_Controller_Router_Route('/filter/*',
                                                array(
                                                    'controller' => 'index',
                                                    'action' => 'index'
                                                )));
    }

    /*
    public static function setupView()
    {
        $view = new Zend_View();
        $view->setEncoding('UTF-8');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    public static function sendResponse(Zend_Controller_Response_Http $response)
    {
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8', true);
        $response->sendResponse();
    }*/

}