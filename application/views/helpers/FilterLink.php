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
            $request = InfoScreen_Model_Request::factory();

            $filterUrl = Zend_Controller_Front::getInstance()->getBaseUrl() . $base;
            $date = $request->getDate();

            // If date not set or same day: omit parameter
            if(!($date === null || InfoScreen_Date::parse($date) == InfoScreen_Date::fromTime(time()))) {
                $filterUrl .= '/date/' . InfoScreen_Date::parse($date);
            }

            $filterUrl .= '/' . $key . '/' . urlencode($value);
            $filterUrl = $request->completeUrl($filterUrl);

            return '<a class="filterlink" data-filterkey="' . $key . '" href="' . $filterUrl . '" title="nach \'' . $value . '\' filtern">' . $value . '</a>';
        }
    }
}