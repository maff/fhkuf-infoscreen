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
        if(!$this->getRequest()->isPost())
        {
            echo 'Hi. I\'m a <abbr title="Simple Object Access Protocol">SOAP</abbr> web service and I never learned how to deal with GET requests, so please feed me with correct data. For more information take a look at the ';
            echo '<a href="' . Zend_Controller_Front::getInstance()->getBaseUrl() . '/service/wsdl/"><abbr title="Web Service Description Language">WSDL</abbr></a> or visit the ';
            echo '<a href="http://maff.ailoo.net/projects/fh-kufstein-raumbelegung-webservice/">project page</a>.';
        }
        else
        {       
            $server = new Zend_Soap_Server('', array());
            $server->setClass('Raumbelegung_Parser_Service');
            $server->setWsdl(self::_getWSDLPath());
            $server->handle();
        }
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