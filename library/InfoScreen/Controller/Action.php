<?php
class InfoScreen_Controller_Action extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->config = $this->getConfig();
        $this->view->ajax = $this->isAjax();

        if($this->isAjax()) {
            $this->disableLayout();
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

    /**
     * @return Zend_Config
     */
    public function getConfig()
    {
        return InfoScreen_Config::getInstance();
    }

    /**
     * @return Zend_Log
     */
    public function getLog()
    {
        return InfoScreen_Log::getInstance();
    }
    
    public function isAjax()
    {
        return $this->getRequest()->isXmlHttpRequest();
    }

    public function isDebug()
    {
        if($this->getRequest()->getParam('debug', false) === 'true') {
            return true;
        }

        return false;
    }
}