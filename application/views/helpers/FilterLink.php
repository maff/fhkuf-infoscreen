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
       	if( strpos($value, ',') !== false )
       	{
       	    $links = array();
       	    $values = explode(',', $value);
       	    foreach($values as $val)
       	        $links[] = $this->filterLink($filter, trim($val));
       	        
       	    return implode(', ', $links);
       	}
       	else if ( strpos($value, '/') !== false || strpos($value, 'NN') !== false)
    	{
    		echo $value;
    	}    	
    	else
    	{
    		$date = Zend_Registry::getInstance()->get('parser_date');
            if($date == strftime('%d.%m.%Y', time()))
                $filterUrl = Zend_Controller_Front::getInstance()->getBaseUrl() . '/filter/' . $filter . '/' . urlencode($value) . '/';
            else
                $filterUrl = Zend_Controller_Front::getInstance()->getBaseUrl() . '/filter/date/' . $date . '/' . $filter . '/' . urlencode($value) . '/';
    		
    		return '<a class="filterlink" rel="' . $filter . '" href="' . $filterUrl . '" title="nach \'' . $value . '\' filtern">' . $value . '</a>';
    	}
    }    
}