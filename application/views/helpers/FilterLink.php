<?php
/**
 * BaseUrl helper
 *
 * @uses helper Zend_View_Helper
 */
class Zend_View_Helper_FilterLink
{
    /**
     * Helper function for outputting filter links
     */
    function filterLink($key, $value, $base = '/day')
    {
       	if(!InfoScreen_Model_List::getInstance($key)->validate($value, false)) {
            return $value;
    	} elseif(strpos($value, ',') !== false) {
            $links = array();
       	    $values = explode(',', $value);

       	    foreach($values as $val) {
       	        $links[] = $this->filterLink($key, trim($val));
            }
       	        
       	    return implode(', ', $links);
       	} else {
            $filterUrl = Zend_Controller_Front::getInstance()->getBaseUrl() . $base;
            $date = InfoScreen_Controller_Request::getDate();

            // If date not set or same day: omit parameter
            if(!($date === null ||  strftime('%d.%m.%Y', strtotime($date)) == strftime('%d.%m.%Y', time()))) {
                $filterUrl .= '/date/' . strftime('%d.%m.%Y', strtotime($date));
            }

            $filterUrl .= '/' . $key . '/' . urlencode($value);    		
            return '<a class="filterlink" rel="' . $key . '" href="' . $filterUrl . '" title="nach \'' . $value . '\' filtern">' . $value . '</a>';
        }
    }
}