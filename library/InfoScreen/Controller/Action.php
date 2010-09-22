<?php
class InfoScreen_Controller_Action extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->config = $this->getConfig();
        $this->view->ajax = $this->isAjax();

        if($this->isAjax()) {
            $this->disableView();
        }
    }

    public function disableLayout()
    {
        $this->_helper->layout()->disableLayout();
    }

    public function disableView()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    protected function _handlePost($url)
    {
        if($this->getRequest()->isPost()) {
            $filterUrl = InfoScreen_Controller_Request::getFilterUrl();
            $this->_redirect($this->getFrontController()->getBaseUrl() . $url . $filterUrl);
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