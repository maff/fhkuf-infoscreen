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
        
        if($this->view->valid === true)
        {
            if(isset($params['format']))
            {
                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);
            
                if($params['format'] == 'outlookCsv')
                {
                    $week = Raumbelegung_Parser_Week::init($date);
                    $week->setFilter(new Raumbelegung_Filter('class', $params['class']));
                    $week->categorizeResults = false;
                    $weekData = $week->getData();
                    
                    $csv = new Raumbelegung_Csv();
                    
                    $headers = new Raumbelegung_Csv_Row();
                    $headers->addColumn("Betreff")
                            ->addColumn("Beginnt am")
                            ->addColumn("Beginnt um")
                            ->addColumn("Endet am")
                            ->addColumn("Endet um")
                            ->addColumn("Ganztägiges Ereignis")
                            ->addColumn("Erinnerung Ein/Aus")
                            ->addColumn("Erinnerung am")
                            ->addColumn("Erinnerung um")
                            ->addColumn("Besprechungsplanung")
                            ->addColumn("Erforderliche Teilnehmer")
                            ->addColumn("Optionale Teilnehmer")
                            ->addColumn("Besprechungsressourcen")
                            ->addColumn("Abrechnungsinformationen")
                            ->addColumn("Beschreibung")
                            ->addColumn("Kategorien")
                            ->addColumn("Ort")
                            ->addColumn("Priorität")
                            ->addColumn("Privat")
                            ->addColumn("Reisekilometer")
                            ->addColumn("Vertraulichkeit")
                            ->addColumn("Zeitspanne zeigen als");
                    
                    $csv->addRow($headers);
                    
                    if(count($weekData > 0)) {
                        foreach($weekData as $entry) {
                            $row = new Raumbelegung_Csv_Row();
                            $row->addColumn($entry['description'])
                                ->addColumn($entry['date'])
                                ->addColumn($entry['startTime'] . ':00')
                                ->addColumn($entry['date'])
                                ->addColumn($entry['endTime'] . ':00')
                                ->addColumn(false)
                                ->addColumn("Aus")
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn($entry['description'] . ' Gruppe ' . $entry['group'] . ' bei ' . $entry['lector'])
                                ->addColumn(false)
                                ->addColumn($entry['room'])
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false)
                                ->addColumn(false);
                                
                            $csv->addRow($row);
                        }
                    }
                    
                    $this->getResponse()
                        ->setHeader('Content-Type', 'text/plain')
                        ->appendBody($csv->toString());                  
                }        
            }
            else
            {
                $parser = new Raumbelegung_Parser();
          
                $week = Raumbelegung_Parser_Week::init($date);
                $week->setFilter(new Raumbelegung_Filter('class', $params['class']));             
                $weekData = $week->getData();
            
                $this->view->week = $weekData;
                $this->view->classes = $parser->getList('class');
                $this->view->date = $date;
                $this->view->title = 'Wochenübersicht KW ' . strftime('%V', strtotime($date));             
            }
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
