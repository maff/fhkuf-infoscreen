<?php
/**
 * Checkselected helper
 *
 * @uses helper Zend_View_Helper
 */
class Zend_View_Helper_CheckSelected
{
    function checkSelected($value, $mode)
    {
    	$request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        
        if(isset($request[$mode]))
        {
            $filtervalue = trim(strtolower(urldecode($request[$mode])));
            $value = trim(strtolower($value));
            
            if($filtervalue == $value)
                echo ' selected="selected"'; 
        }   		
    }    
}