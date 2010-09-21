<?php
class InfoScreen_Cache
{
    /**
     * Get a cache instance saved in registry
     *
     * @param  string $key
     * @return Zend_Cache_Core
     */
    public static function get($key)
    {
        return Zend_Registry::get('cache.' . $key);
    }
}