<?php
class InfoScreen_Config
{
    public static function getInstance()
    {
        return Zend_Registry::getInstance()->get('config');
    }
}