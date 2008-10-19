<?php
class Zend_View_Helper_MenuLink
{
	function menuLink($mode)
	{
		$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();

        $url = '';        
        foreach(array('class', 'date') as $valid)
        {
            if(isset($params[$valid]))
                $url .= $valid . '/' . $params[$valid] . '/';
        }
            
        if($mode == 'base')
        {
            if(empty($url))
                $url = '/';
            else
                $url = '/filter/' . $url;
        }
        elseif($mode == 'week')
        {
            if(empty($url))
                $url = '/week';
            else
                $url = '/week/show/' . $url;
        }

        return $url;
	}
}