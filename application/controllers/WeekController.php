<?php
class WeekController extends Zend_Controller_Action
{
    protected $_parser;
    protected $_redirector = null;
    protected $_ajax = false;
    
    public function preDispatch ()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        //$this->_handlePost();
    }
    
	public function indexAction ()
	{
        $week = new Raumbelegung_Parser_Week();
        $week->setFilter(new Raumbelegung_Filter('class', 'wi07-vz'));        
        $this->view->week = $week->getData();
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
}
