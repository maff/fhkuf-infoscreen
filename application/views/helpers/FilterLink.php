<?php
/**
 * BaseUrl helper
 *
 * @uses helper Zend_View_Helper
 */
class Zend_View_Helper_FilterLink
{
    /**
     * helper function for outputting filter links
     */
    function filterLink($filter, $value)
    {
        echo $value;
        return;
        
    	global $fDate;
    	
    	if( strpos($value, ',') !== false ||
    		strpos($value, '/') !== false ||
    		strpos($value, 'NN') !== false)
    	{
    		echo $value;
    	}    	
    	else
    	{
    		$filterUrl = BASE_URL;
    		$filterUrl .= 'date/' . $fDate . '/' . $filter . '/' . urlencode($value) . '/';
    		
    		echo '<a href="' . $filterUrl . '" title="nach \'' . $value . '\' filtern">' . $value . '</a>';
    	}
    }    
}