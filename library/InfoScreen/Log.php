<?php
class InfoScreen_Log extends Zend_Log
{
    /**
     * Get log instance
     *
     * @return Zend_Log
     */
    public static function getInstance()
    {
        return Zend_Registry::getInstance()->get('log');
    }

    /**
     * Construct filter object from configuration array or Zend_Config object
     *
     * @param  array|Zend_Config $config Zend_Config or Array
     * @return Zend_Log_Filter_Interface
     */
    public static function constructFilterFromConfig($config)
    {
        return self::getInstance()->_constructFilterFromConfig($config);
    }
}