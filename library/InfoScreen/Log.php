<?php
class InfoScreen_Log
{
    /**
     *
     * @return Zend_Log
     */
    public static function getInstance()
    {
        return Zend_Registry::getInstance()->get('log');
    }
}