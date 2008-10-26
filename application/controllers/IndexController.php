<?php
class IndexController extends Zend_Controller_Action
{
    protected $_parser;
    protected $_redirector = null;
    protected $_ajax = false;
    
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
		
	    $title = strftime('%A, %d.%m.%Y', strtotime($this->_parser->getDate()));
	    $data = $this->_parser->getData();
		
        if(count($data) > 0)
        {
            $results = true;
            $error = '';
        }
		else
        {
            $results = false;
            $error = 'Für die angegebenen Filterkriterien gibt es keine Ergebnisse.';
        }
            
        if($this->_ajax)
        {
            $this->_helper->layout()->disableLayout();
            $this->view->ajax = true;
        }

        $this->view->title = $title;
        $this->view->results = $results;
        $this->view->error = $error;
        $this->view->date = $this->_parser->getDate();
        $this->view->classes = $this->_parser->getList('class');
        $this->view->lectors = $this->_parser->getList('lector');
        $this->view->rooms = $this->_parser->getList('room');
        $this->view->appointments = $data;
        $this->view->filterUrl = $this->_getFilterUrl($filters);
	}
	
	private function _handlePost()
	{
	    if ($this->getRequest()->isPost())
        {
            $urlstring = '/';
            $url = array();
            $params = $this->getRequest()->getParams();
            
            if(isset($params['date']) && $params['date'] == strftime('%d.%m.%Y', time()))
                $valid_params = array('class', 'lector', 'room');
            else 
                $valid_params = array('date', 'class', 'lector', 'room');
                
            foreach($valid_params as $key)
            {
               if(isset($params[$key]) && !empty($params[$key]))
               {
                   $url[] = $key;
                   $url[] = urlencode(strtolower($params[$key]));
               }
            }
            
            if(count($url) > 0)
                $urlstring = '/filter/' . implode('/', $url) . '/';
                
            $this->_redirector->gotoUrl($urlstring);
        }       
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
        
        if(isset($request['ajax']) && (bool) $request['ajax'] == true)
            $this->_ajax = true;
        
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
    
    protected function _getFilterUrl($filters)
    {
        $url = '';
        if(is_array($filters) && count($filters) > 0)
        {
            $parts = array();
            foreach($filters as $filter)
            {
                $parts[] = $filter->key;
                $parts[] = $filter->value;
            }
            
            $url = implode('/', $parts);
        }
        
        return $url;
    }
}
