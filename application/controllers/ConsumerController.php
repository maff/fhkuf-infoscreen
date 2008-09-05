<?php
class ConsumerController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout(); 
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
    }
    
    public function indexAction()
    {
        $client = new SoapClient('/var/www/zftest/application/config/Service.wsdl');
        $client->getList('lector');
    }    
}