<?php
class InfoScreen_Log
{
    public static function getInstance()
    {
        return Zend_Registry::getInstance()->get('log');
    }
}