<?php
// some general settings
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Vienna');

// directory setup and class loading
set_include_path('.' . PATH_SEPARATOR . '../library/'
. PATH_SEPARATOR . '../application/models'
. PATH_SEPARATOR . '../application/forms'
. PATH_SEPARATOR . get_include_path());

include 'Zend/Loader.php';
Zend_Loader::registerAutoload();
Zend_Layout::startMvc(array('layoutPath' => '../application/layouts'));

// load configuration
$config = new Zend_Config_Ini('../application/config/config.ini', 'settings');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);

// setup database
//$db = Zend_Db::factory($config->db);
//Zend_Db_Table::setDefaultAdapter($db);
//$registry->set('db', $db);

// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);
$frontController->setControllerDirectory('../application/controllers');

// set doctype
$documentType = new Zend_View_Helper_Doctype();
$documentType->doctype('XHTML1_STRICT');

// setup Access Control Lists
// $frontController->registerPlugin(new My_Plugin_Acl(Zend_Auth::getInstance(), new My_Acl()));


$router = $frontController->getRouter();
$router->addRoute(
    'filter',
    new Zend_Controller_Router_Route('*',
                                     array('controller' => 'index',
                                           'action' => 'index'))
);

// run!
$frontController->dispatch();