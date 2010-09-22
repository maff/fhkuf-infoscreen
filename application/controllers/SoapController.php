<?php
class SoapController extends InfoScreen_Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('/api/soap/endpoint');
    }

    public function endpointAction()
    {
        if($this->getRequest()->isPost()) {
            $this->disableLayout();
            $this->disableView();
        }
    }

    public function wsdlAction()
    {
        $this->disableLayout();
        $this->getResponse()->setHeader('Content-type', 'text/xml');
        $this->_helper->viewRenderer->setViewSuffix('wsdl');
    }
}