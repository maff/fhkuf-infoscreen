<?php
class ConsumerController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        //$this->_helper->layout()->disableLayout(); 
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
    }
    
    public function indexAction()
    {
        $client = new SoapClient('http://zftest/service/wsdl/');
        
        $filters = array();
        //$filters[] = new Raumbelegung_Filter('lector', 'Kaspar');
        $filters[] = new Raumbelegung_Filter('class', 'V2008');
        //$filters[] = new Raumbelegung_Filter('room', '1.13');
        
        Zend_Debug::dump($client->getResults('today', $filters));
        Zend_Debug::dump($client->getList('lector', false));
    }    
}