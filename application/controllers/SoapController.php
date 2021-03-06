<?php
class SoapController extends InfoScreen_Controller_Action
{
    public function indexAction()
    {
        if($this->getRequest()->isPost()) {
            $this->disableLayout();
            $this->disableView();
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=utf-8');

            $wsdl = InfoScreen_Config::getInstance()->base_url . '/api/soap/wsdl';

            $server = new Zend_Soap_Server(null, array('encoding' => 'UTF-8'));
            $server->setClass('InfoScreen_Model_Soap');
            $server->setWsdl($wsdl);
            $server->handle();
        }
    }

    public function wsdlAction()
    {
        $this->disableLayout();
        $this->getResponse()->setHeader('Content-type', 'text/xml; charset=utf-8');
        $this->_helper->viewRenderer->setViewSuffix('wsdl');
    }
}