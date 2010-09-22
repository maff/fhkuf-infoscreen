<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initLocale()
    {
        setlocale (LC_ALL, 'de_DE');
        date_default_timezone_set('Europe/Vienna');
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
    }

    protected function _initLibraries()
    {
        require_once 'simple_html_dom.php';
        require_once 'iCalcreator.class.php';
    }

    protected function _initDataCache()
    {
        /**
         * @var Zend_Config
         */
        $config = InfoScreen_Config::getInstance()->cache;

        $backendOptions = array();
        if(isset($config->data->backend->options)) {
            $backendOptions = $config->data->backend->options->toArray();
        }

        foreach ($config->data->type as $type => $cfg) {
            $frontendOptions = $cfg->frontend->options->toArray();
            $frontendOptions['cache_id_prefix'] = $config->prefix . 'data_';
            $frontendOptions['automatic_serialization'] = true;

            $cache = Zend_Cache::factory(
                $cfg->frontend->type,
                $config->data->backend->type,
                $frontendOptions,
                $backendOptions
            );

            Zend_Registry::set('cache.data.' . $type, $cache);
        }
    }

    protected function _initListCache()
    {
        /**
         * @var Zend_Config
         */
        $config = InfoScreen_Config::getInstance()->cache;

        $backendOptions = array();
        if(isset($config->list->backend->options)) {
            $backendOptions = $config->list->backend->options->toArray();
        }

        $frontendOptions = $config->list->frontend->options->toArray();
        $frontendOptions['cache_id_prefix'] = $config->prefix . 'list_';
        $frontendOptions['automatic_serialization'] = true;

        $cache = Zend_Cache::factory(
            $config->list->frontend->type,
            $config->list->backend->type,
            $frontendOptions,
            $backendOptions
        );

        Zend_Registry::set('cache.list', $cache);
    }
}