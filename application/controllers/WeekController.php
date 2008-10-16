<?php
class WeekController extends Zend_Controller_Action
{
    protected $_parser;
    protected $_redirector = null;
    protected $_ajax = false;
    
    public function preDispatch ()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->_handlePost();
    }
    
	public function indexAction ()
	{
        $startDate = $this->_getStartDate();
             
	}
    

    protected function _getStartDate()
    {
        /*$startDate = null
        if($date = $this->_getDate())
        {
            if($start = strtotime($date))
                $startTime = $start;
        }
        else
        {
            $startTime = strtotime("last monday");
        }    
        
        $weekDay = date('w', $startTime)
        {
            if($weekDay == 0)
        }*/
        
        
        
        
    }

	protected function _getDate()
	{
	    $request = $this->getRequest()->getParams();
	    if(isset($request['date']))
	        return $request['date'];
	        
	    return false;
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
}
