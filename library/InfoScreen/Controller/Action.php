<?php
class InfoScreen_Controller_Action extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->config = $this->getConfig();
        $this->view->ajax = $this->isAjax();

        if($this->isAjax()) {
            $this->_helper->layout()->disableLayout();
        }
    }
    
    public function getConfig()
    {
        return InfoScreen_Config::getInstance();
    }
    
    public function isAjax()
    {
        return $this->getRequest()->isXmlHttpRequest();
    }
}