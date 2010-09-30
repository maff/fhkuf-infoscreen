<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initLocale()
    {
        setlocale (LC_ALL, 'de_DE');
        date_default_timezone_set('Europe/Vienna');
    }

    protected function _initBaseUrl()
    {
        $protocol = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $protocol .= 's';
        }

        $port = '';
        if(!in_array($_SERVER['SERVER_PORT'], array(80, 443))) {
            $port = ':' . $_SERVER['SERVER_PORT'];
        }

        $path = dirname($_SERVER['SCRIPT_NAME']);
        $base_url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $port;
        $base_url .= (strlen($path) > 1) ? $path : '';

        /* @var $config Zend_Config */
        $config = InfoScreen_Config::getInstance();
        $config->base_url = $base_url;
    }

    protected function _initLibraries()
    {
        require_once 'simple_html_dom.php';
        require_once 'iCalcreator.class.php';
        require_once 'PiwikTracker.php';
    }

    protected function _initLogging()
    {
        $this->bootstrap('Log');

        /* @var $log Zend_Log */
        $log = $this->getResource('Log');
        Zend_Registry::getInstance()->set('log', $log);

        /* @var $config Zend_Config */
        $config = InfoScreen_Config::getInstance();

        // log factory doesn't allow mail writers, create them manually
        if(isset($config->loggers->email)) {
            foreach($config->loggers->email as $cfg) {
                $mail = new Zend_Mail();
                $mail->setSubject($cfg->subject);
                $mail->addTo($cfg->recipient);

                $writer = new Zend_Log_Writer_Mail($mail);

                if(isset($cfg->filterName)) {
                    $filter = InfoScreen_Log::constructFilterFromConfig($cfg);
                    $writer->addFilter($filter);
                }

                $log->addWriter($writer);
            }
        }
    }

    protected function _initRouting()
    {
        $this->bootstrap('FrontController');

        /* @var $front Zend_Controller_Front */
        $front = $this->getResource('FrontController');

        /* @var $router Zend_Controller_Router_Interface */
        $router = $front->getRouter();
        $router->removeDefaultRoutes();

        $router->addRoute('index',
            new Zend_Controller_Router_Route('/',
                array(
                    'controller' => 'index',
                    'action' => 'index'
        )));

        $router->addRoute('day',
            new Zend_Controller_Router_Route('/day/*',
                array(
                    'controller' => 'index',
                    'action' => 'day'
        )));

        $router->addRoute('week',
            new Zend_Controller_Router_Route('/week/*',
                array(
                    'controller' => 'index',
                    'action' => 'week'
        )));

        $router->addRoute('api',
            new Zend_Controller_Router_Route('/api',
                array(
                    'controller' => 'api',
                    'action' => 'index'
        )));

        $router->addRoute('api.generator',
            new Zend_Controller_Router_Route('/api/generator',
                array(
                    'controller' => 'api',
                    'action' => 'generator'
        )));

        $router->addRoute('api.ical',
            new Zend_Controller_Router_Route('/api/ical/*',
                array(
                    'controller' => 'api',
                    'action' => 'ical'
        )));

        $router->addRoute('api.json',
            new Zend_Controller_Router_Route('/api/json/*',
                array(
                    'controller' => 'api',
                    'action' => 'json'
        )));

        $router->addRoute('api.xml',
            new Zend_Controller_Router_Route('/api/xml/*',
                array(
                    'controller' => 'api',
                    'action' => 'xml'
        )));

        $router->addRoute('soap',
            new Zend_Controller_Router_Route('/api/soap/*',
                array(
                    'controller' => 'soap',
                    'action' => 'index'
        )));

        $router->addRoute('soap.wsdl',
            new Zend_Controller_Router_Route('/api/soap/wsdl/*',
                array(
                    'controller' => 'soap',
                    'action' => 'wsdl'
        )));

        $router->addRoute('infoscreen.js',
            new Zend_Controller_Router_Route('/js/infoscreen.js',
                array(
                    'controller' => 'js',
                    'action' => 'infoscreen'
        )));

        $router->addRoute('day.js',
            new Zend_Controller_Router_Route('/js/day.js',
                array(
                    'controller' => 'js',
                    'action' => 'day'
        )));

        $router->addRoute('week.js',
            new Zend_Controller_Router_Route('/js/week.js',
                array(
                    'controller' => 'js',
                    'action' => 'week'
        )));

        $router->addRoute('api.js',
            new Zend_Controller_Router_Route('/js/api.js',
                array(
                    'controller' => 'js',
                    'action' => 'api'
        )));
    }
}