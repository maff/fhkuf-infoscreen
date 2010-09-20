<?php
class InfoScreen_Controller_Action extends Zend_Controller_Action
{
    protected $_ajax = false;   
 
    public function init()
    {
        $this->_checkAjax();
        $this->view->config = $this->getConfig();
    }
    
    public function getConfig()
    {
        return InfoScreen_Config::getInstance();
    }
    
    protected function _checkAjax()
    {
        if($this->getRequest()->isXmlHttpRequest())
        {
            $this->_ajax = true;
        }
    }
}