<?php
class InfoScreen_Controller_Request
{
    public static function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }

    public static function getFilterUrl($date = true, $prefix = '/')
    {
        $url = array();

        if($date && self::getDate() != null) {
            $url[] = 'date';
            $url[] = self::getDate();
        }

        $rf = self::getRequestFilters();
        if(count($rf) > 0) {
            foreach($rf as $key => $value) {
                $url[] = $key;
                $url[] = $value;
            }
        }
        
        if(count($url) > 0) {
            return self::buildUrl($prefix . implode('/', $url));
        }

        return self::buildUrl('');
    }

    public static function getDate()
    {
        return self::getRequest()->getParam('date', null);
    }

    public static function getDateUrl($prefix = '/')
    {
        $date = self::getDate();
        if($date == null) {
            return '';
        }

        return $prefix . 'date/' . $date;
    }

    public static function getRequestFilters()
    {
        $allowed_params = array('class', 'lector', 'room');

        $filters = array();
        foreach($allowed_params as $key) {
            if(self::getRequest()->getParam($key, false)) {
                $filters[$key] = self::getRequest()->getParam($key);
            }
        }

        return $filters;
    }

    public static function getFilters()
    {
        $filters = array();
        $rf = self::getRequestFilters();
        if(count($rf) > 0) {
            foreach($rf as $key => $value) {
                if($key == 'class' && !self::isStrictFiltering()) {
                    $filters[] = new InfoScreen_Model_Filter_ClassAndYear($value);
                } else {
                    $filters[] = new InfoScreen_Model_Filter($key, $value);
                }
            }
        }

        return $filters;
    }

    public static function isStrictFiltering()
    {
        if(self::getRequest()->getParam('strict', false) === 'true') {
            return true;
        }

        return false;
    }

    public static function buildUrl($url)
    {
        if(self::isStrictFiltering() && strpos($url, 'strict') === false) {
            $url .= '/strict/true';
        }

        return $url;
    }
}
