<?php
class Zend_View_Helper_MenuLink
{
    function menuLink($mode)
    {
        $link = Zend_Controller_Front::getInstance()->getBaseUrl();
        $link .= '/' . $mode . '/';
        $link .= InfoScreen_Controller_Request::getFilterUrl();

        return $link;
    }
}