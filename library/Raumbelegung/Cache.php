<?php
class Raumbelegung_Cache
{
    public static function get($key)
    {
        return Zend_Registry::get('cache.' . $key);
    }
}