<?php
class IndexController extends Zend_Controller_Action
{
    protected $_parser;
    protected $_redirector = null;
    
    public function preDispatch ()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->_handlePost();
        $this->_parser = new Raumbelegung_Parser($this->_getDate());
    }
    
	public function indexAction ()
	{
	    $filters = $this->_getFilters();
	    if(count($filters) > 0)
	        $this->_parser->addFilters($filters);
		
	    $data = $this->_parser->getData();
		if(count($data) > 0)
		    $this->view->title = 'Ergebnisse fuer ' . $this->_getDate();
				else
		    $this->view->title = 'Keine Ergebnisse'; 
		
		$this->view->date = $this->_getDate();
		$this->view->lectors = $this->_parser->getList('lector');
		$this->view->classes = $this->_parser->getList('class');
		$this->view->rooms = $this->_parser->getList('room');
	    $this->view->appointments = $data;
	}
	
	private function _handlePost()
	{
	    if ($this->getRequest()->isPost())
        {
            $url = array();
            $params = $this->getRequest()->getParams();
            foreach(array('date', 'class', 'lector', 'room') as $key)
            {
               if(isset($params[$key]) && !empty($params[$key]))
               {
                   $url[] = $key;
                   $url[] = $params[$key];
               }
            }           
           
            $this->_redirector->gotoUrl(implode('/', $url));
        }
        
        return;
	}
	
	protected function _getDate()
	{
	    $date = '';
	    $request = $this->getRequest()->getParams();
	    if(isset($request['date']))
	        $date = $request['date'];
	    
	    if(empty($date))
            $date = strftime('%d.%m.%Y', time());
        else
            $date = strftime('%d.%m.%Y', strtotime($date));
        
        return $date;
	}
	
	protected function _getFilters()
	{
	    $request = $this->getRequest()->getParams();
	    $params = array();
	    $allowed_params = array('lector', 'class', 'room');
	    if(is_array($request) && count($request) > 0)
	    {
	        foreach($request as $key => $value)
	        {
	            if(in_array($key, $allowed_params) && !empty($value))
	            {
                    $params[$key] = $value;
	            }
	        }
	    }
	    
	    return $params;
	}
	
	protected function _assignData()
	{
	    $this->view->appointments = $this->_parser->getData();
	}
}