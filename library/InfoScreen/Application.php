<?php
class InfoScreen_Application extends Zend_Application
{
    /**
     * Constructor
     *
     * Initialize application. Potentially initializes include_paths, PHP
     * settings, and bootstrap class.
     *
     * @param  string                   $environment
     * @param  string|array|Zend_Config $options String path to configuration file, or array/Zend_Config of configuration options
     * @throws Zend_Application_Exception When invalid options are provided
     * @return void
     */
    public function __construct($environment, Zend_Config $config)
    {
        Zend_Registry::set('config', $config);
        parent::__construct($environment, $config);
    }
}