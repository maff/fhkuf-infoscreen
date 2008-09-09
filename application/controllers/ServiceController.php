<?php
class ServiceController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout(); 
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
    }
    
    public function indexAction()
    {
//        if(!$this->getRequest()->isPost())
//        {
//            $this->_redirector->gotoUrl('/');
//            return;
//        }
//        else
//        {       
            $server = new Zend_Soap_Server('', array());
            $server->setClass('Raumbelegung_Parser_Service');
            $server->setWsdl(self::_getWSDLPath());
            $server->handle();
//        }
    }
    
    public function wsdlAction()
    {
        $this->getResponse()->setHeader('Content-type', 'text/xml');
        $this->getResponse()->sendHeaders();
        
        echo self::_getWSDL();
    }
    
    protected static function _getWSDL()
    {
        return file_get_contents(self::_getWSDLPath());    
    }
    
    protected static function _getWSDLPath()
    {
        return Zend_Registry::getInstance()->get('base_path') . '/application/config/service.wsdl';
    }
 
}