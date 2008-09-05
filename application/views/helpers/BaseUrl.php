<?php
/**
 * BaseUrl helper
 *
 * @uses helper Zend_View_Helper
 */
class Zend_View_Helper_BaseUrl
{
	function baseUrl()
	{
		$fc = Zend_Controller_Front::getInstance();
		return $fc->getBaseUrl();
	}
}