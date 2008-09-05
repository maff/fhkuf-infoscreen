<?php
class Raumbelegung_Config
{   
    static public function get($key)
    {
        return Zend_Registry::getInstance()->get('config')->$key;
    }
}