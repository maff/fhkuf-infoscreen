<?php
class InfoScreen_Cache
{
    /**
     * Get a cache instance from manager
     *
     * @param  string $key
     * @return Zend_Cache_Core
     */
    public static function get($key)
    {
        /* @var $manager Zend_Cache_Manager */
        $manager = Zend_Controller_Front::getInstance()
                    ->getParam('bootstrap')
                    ->getResource('cachemanager');

        return $manager->getCache($key);
    }
}