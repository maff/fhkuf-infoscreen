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
        self::setupErrorHandling();
        self::dispatch();
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
        self::$frontController->setControllerDirectory('application/controllers');
        self::$frontController->throwExceptions(false);
        self::$frontController->returnResponse(false);        
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
    
    public static function setupErrorHandling()
    {
        self::$frontController->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
            'module'     => 'default',
            'controller' => 'error',
            'action'     => 'error'
        )));   
    }
    
    public static function dispatch()
    {   
        /*$response = self::$frontController->dispatch();
        if ($response->isException()) {
            $exceptions = $response->getException();
            Zend_Debug::dump($exceptions);
        } else {
            $response->sendHeaders();
            $response->outputBody();
        }*/        
    
        try
        {
            self::$frontController->dispatch();
        }
        catch (Exception $e)
        {
            //self::$frontController->getRequest()->_forward('error', 'error');
            //echo 'catched';
            // catch any errors, log them and redirect to the 500 Internal Server Error page        
            /*$error = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
            $error->type = Raumbelegung_Error_Handler::EXCEPTION_CATCHED_EXCEPTION;
        
            self::$frontController->getRequest()->setParam('error_handler', $error)
                    ->setModuleName('default')
                    ->setControllerName('error')
                    ->setActionName('error')
                    ->setDispatched(false);*/
                    
            //self::$frontController->sendResponse();
                    
            //self::$frontController->dispatch();
        }
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
