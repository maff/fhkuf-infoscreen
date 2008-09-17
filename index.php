<?php
require_once 'Bootstrap.php';
Bootstrap::run();
exit(0);






//header('Location: http://stud.ailoo.net/raumbelegung/down.php');

// some general settings
// error_reporting(E_ALL|E_STRICT);
error_reporting(0);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Vienna');

// directory setup and class loading
set_include_path('.' . PATH_SEPARATOR . 'library/'
. PATH_SEPARATOR . 'application/models'
. PATH_SEPARATOR . 'application/forms'
. PATH_SEPARATOR . get_include_path());

include 'Zend/Loader.php';
Zend_Loader::registerAutoload();
Zend_Layout::startMvc(array('layoutPath' => 'application/layouts'));

// load configuration
$config = new Zend_Config_Ini('application/config/config.ini', 'settings');

$registry = Zend_Registry::getInstance();
$registry->set('config', $config);
$registry->set('base_path', dirname(__FILE__));

// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);
$frontController->setControllerDirectory('application/controllers');
$frontController->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());

// set doctype
$documentType = new Zend_View_Helper_Doctype();
$documentType->doctype('XHTML1_STRICT');

$router = $frontController->getRouter();
$router->addRoute(
    'filter',
    new Zend_Controller_Router_Route('/filter/*',
                                     array('controller' => 'index',
                                           'action' => 'index'))
);

//$router->addRoute('service', new Zend_Controller_Router_Route('/service/*', array('controller' => 'service', 'action' => 'index')));
//$router->addRoute('service', new Zend_Controller_Router_Route('/service/:action/', array('controller' => 'service')));

//$router->addRoute('consumer', new Zend_Controller_Router_Route('/consumer/*', array('controller' => 'consumer', 'action' => 'index')));
//$router->addRoute('consumer', new Zend_Controller_Router_Route('/consumer/:action/', array('controller' => 'consumer')));

// run!
//try
//{
    $frontController->dispatch();
//}
// catch(Exception $e)
// {
    // header("HTTP/1.0 404 Not Found");
    // echo 'Error 404: Not found. <a href="' . $config->base_url . '">Front page</a>.';
    // die();
// }