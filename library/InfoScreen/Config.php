<?php
class InfoScreen_Config
{
    /**
     * Get config instance
     *
     * @return Zend_Config
     */
    public static function getInstance()
    {
        return Zend_Registry::getInstance()->get('config');
    }
}