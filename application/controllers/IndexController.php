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
	        $this->_parser->setFilters($filters);
		
	    $data = $this->_parser->getData();
		if(count($data) > 0)
		    $this->view->title = 'Ergebnisse für ' . $this->_parser->getDate();
		else
		    $this->view->title = 'Keine Ergebnisse'; 
		
		$this->view->date = $this->_parser->getDate();
		$this->view->classes = $this->_parser->getList('class');
		$this->view->lectors = $this->_parser->getList('lector');
		$this->view->rooms = $this->_parser->getList('room');
	    $this->view->appointments = $data;
	}
	
	private function _handlePost()
	{
	    if ($this->getRequest()->isPost())
        {
            $url = array();
            $params = $this->getRequest()->getParams();
            if(isset($params['reset']))
            {
                $urlstring = '/';
                if(isset($params['date']) && !empty($params['date']))
                    $urlstring = '/filter/date/' . $params['date'] . '/';
            }
            else
            {            
                foreach(array('date', 'class', 'lector', 'room') as $key)
                {
                   if(isset($params[$key]) && !empty($params[$key]))
                   {
                       $url[] = $key;
                       $url[] = urlencode($params[$key]);
                   }
                }
                
                if(count($url) > 0)
                    $urlstring = '/filter/' . implode('/', $url) . '/';
                else
                    $urlstring = '/';
            }       
           
            $this->_redirector->gotoUrl($urlstring);
        }
        
        return;
	}
	
	protected function _getDate()
	{
	    $date = '';
	    $request = $this->getRequest()->getParams();
	    if(isset($request['date']))
	        $date = $request['date'];
	        
	    return $date;
	}
	
	protected function _getFilters()
	{
	    $request = $this->getRequest()->getParams();
	    $filters = array();
	    $allowed_params = array('class', 'lector', 'room');
	    if(is_array($request) && count($request) > 0)
	    {
	        foreach($request as $key => $value)
	        {
	            if(in_array($key, $allowed_params) && !empty($value))
	            {
                    $filters[] = new Raumbelegung_Filter($key, $value);
	            }
	        }
	    }
	    
	    return $filters;
	}
}