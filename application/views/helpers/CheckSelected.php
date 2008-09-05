<?php
/**
 * BaseUrl helper
 *
 * @uses helper Zend_View_Helper
 */
class Zend_View_Helper_CheckSelected
{
    function checkSelected($value, $mode)
    {
    	$request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    	if(isset($request[$mode]) && (trim(urldecode($request[$mode])) == $value))
    		echo ' selected="selected"'; 
    }    
}