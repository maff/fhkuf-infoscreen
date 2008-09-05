<?php
class ServiceController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout(); 
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
    }
    
    public function restAction()
    {
        $server = new Zend_Rest_Server();
        $server->setClass('Raumbelegung_Parser_Service_Abstract');
        $server->handle();
    }
    
    public function soapAction()
    {
//        $params = $this->getRequest()->getParams();
//        if(!(isset($params['wsdl']) || $this->getRequest()->isPost()))
//        {
//            $this->_redirector->gotoUrl('/');
//            return;
//        }
//        else
//        {
            require_once 'NuSoap/nusoap.php';
            
            $namespace = "http://fhkufstein-raumbelegung/";
            $server = new nusoap_server();
        	$server->soap_defencoding = 'UTF-8';
        	$server->decode_utf8 = false;
        	$server->setEndpoint('http://zftest/service/soap/');	
        	
            $server->configureWSDL('raumbelegung', $namespace);
            $server->wsdl->schemaTargetNamespace = $namespace;
        				
        	$server->wsdl->addComplexType(
        		'Appointment',
        		'complexType',
        		'struct',
        		'all',
        		'',
        		array(
        			'startTime' => array('name' => 'startTime',
        			 	'type' => 'xsd:string'),
        			'endTime' => array('name' => 'endTime',
        			 	'type' => 'xsd:string'),
        			'class' => array('name' => 'class',
        			 	'type' => 'xsd:string'),
        			'description' => array('name' => 'description',
        			 	'type' => 'xsd:string'),
        			'room' => array('name' => 'room',
        			 	'type' => 'xsd:string'),
        			'lector' => array('name' => 'lector',
        			 	'type' => 'xsd:string'),
        			'info' => array('name' => 'info',
        			 	'type' => 'xsd:string')
        		)
        	);
        		
        	$server->wsdl->addComplexType(
        	  'Appointments',
        	  'complexType',
        	  'array',
        	  '',
        	  'SOAP-ENC:Array',
        	  array(),
        	  array(
        	    array('ref' => 'SOAP-ENC:arrayType',
        	         'wsdl:arrayType' => 'tns:Appointment[]')
        	  ),
        	  'tns:Appointment'
        	);
        	
        	$server->wsdl->addComplexType(
        		'Filter',
        		'complexType',
        		'struct',
        		'all',
        		'',
        		array(
        			'key' => array('name' => 'key',
        			 	'type' => 'xsd:string'),
        			'value' => array('name' => 'value',
        			 	'type' => 'xsd:string')
        		)
        	);
        	
        	$server->wsdl->addComplexType(
        	  'Filters',
        	  'complexType',
        	  'array',
        	  '',
        	  'SOAP-ENC:Array',
        	  array(),
        	  array(
        	    array('ref' => 'SOAP-ENC:arrayType',
        	         'wsdl:arrayType' => 'tns:Filter[]')
        	  ),
        	  'tns:Filter'
        	);
        	
        	$server->wsdl->addComplexType(
        	  'List',
        	  'complexType',
        	 'array',
        	  '',
        	  'SOAP-ENC:Array',
        	  array(),
        	  array(
        	    array('ref' => 'SOAP-ENC:arrayType',
        	         'wsdl:arrayType' => 'xsd:string[]')
        	  ),
        	  'xsd:string'
        	);
        				
        	$server->register('Raumbelegung_Parser_Service.getResults',
        		array('date' => 'xsd:string', 'filters' => 'tns:Filters'),
        		array('result' => 'tns:Appointments'),
        		$namespace
        	);
        	
        	$server->register('Raumbelegung_Parser_Service.getList',
        		array('key' => 'xsd:string'),
        		array('result' => 'tns:List'),
        		$namespace
        	);
        	
        	
        	$server->service(file_get_contents("php://input"));
        //}
    }
    
    public function getResultsAction()
    {
        echo 'getResults';
    }
    
    public function getListAction()
    {
        echo 'getList';
    }
}