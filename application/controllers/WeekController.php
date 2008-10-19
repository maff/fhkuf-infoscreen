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
    
    public function indexAction()
    {
        $this->_helper->layout()->disableLayout();
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);

        $this->_redirector->gotoUrl('/week/show');
    }
    
	public function showAction ()
	{
        $this->_handlePost();
    
        $params = $this->getRequest()->getParams();
        
        $this->view->valid = true;
        if(!isset($params['class']) || empty($params['class']))
            $this->view->valid = false;
        
        $date = null;
        if(isset($params['date']))
        {
            if($timestamp = strtotime($params['date']))
            {
                $date = strftime('%d.%m.%Y', $timestamp);
            }
        }
        
        if($date == null)
        {
            $date = strftime('%d.%m.%Y', time());
        }
        
        $this->view->date = $date;
        $this->view->title = 'Wochenübersicht KW ' . strftime('%V', strtotime($date));
 
        $parser = new Raumbelegung_Parser();
        $this->view->classes = $parser->getList('class');
        
        if($this->view->valid === true)
        {
            $week = Raumbelegung_Parser_Week::init($date);
            $week->setFilter(new Raumbelegung_Filter('class', $params['class']));                
            $this->view->week = $week->getData();
        }
	}
    
    
	private function _handlePost()
	{
	    if ($this->getRequest()->isPost())
        {
            $params = $this->getRequest()->getParams();

            $urlstring = '/' . $params['controller'] . '/' . $params['action'];
            $url = array();
            
            $valid_params = array('date', 'class');
                
            foreach($valid_params as $key)
            {
               if(isset($params[$key]) && !empty($params[$key]))
               {
                   $url[] = $key;
                   $url[] = urlencode(strtolower($params[$key]));
               }
            }
            
            if(count($url) > 0)
                $urlstring .= '/' . implode('/', $url) . '/';
                
            $this->_redirector->gotoUrl($urlstring);
        }       
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
