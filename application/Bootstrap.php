<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDomParser()
    {
        require_once('simple_html_dom.php');
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