<?php
class InfoScreen_Controller_Request
{
    public static function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }

    public static function getFilterUrl($date = true)
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
            return implode('/', $url);
        }

        return '';
    }

    public static function getDate()
    {
        return self::getRequest()->getParam('date', null);
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
                if($key == 'class') {
                    $filters[] = new InfoScreen_Model_Filter_ClassAndYear($value);
                } else {
                    $filters[] = new InfoScreen_Model_Filter($key, $value);
                }
            }
        }

        return $filters;
    }
}
