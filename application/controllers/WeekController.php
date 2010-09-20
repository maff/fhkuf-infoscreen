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

        if(isset($params['format']))
        {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            
            $week = Raumbelegung_Parser_Week::init($date);
            
            if(isset($params['class']) && !empty($params['class'])) {
                $week->setFilter(new Raumbelegung_Filter('class', $params['class']));
            }
            
            $week->categorizeResults = false;
            $weekData = $week->getData();
        
            if($params['format'] == 'ical') {
                if(count($weekData) > 0) {
                    require_once 'iCalcreator.class.php';
                    
                    $v = new vcalendar();
                    $v->setConfig('unique_id', 'ailoo.net raumbelegung');
                    $v->setProperty('method', 'PUBLISH');
                    $v->setProperty('x-wr-calname', 'ailoo.net Raumbelegung iCal Interface');
                    $v->setProperty('X-WR-CALDESC', 'ailoo.net Raumbelegung iCal Interface');
                    $v->setProperty('X-WR-TIMEZONE', 'Europe/Vienna');

                    foreach($weekData as $entry) {
                        $date = explode('.', $entry['date']);
                        $time[0] = explode(':', $entry['startTime']);
                        $time[1] = explode(':', $entry['endTime']);
                    
                        $startEvent = array('year' => $date[2],
                                            'month' => $date[1],
                                            'day' => $date[0],
                                            'hour' => $time[0][0],
                                            'min' => $time[0][1],
                                            'sec' => 0);
                                            
                        $endEvent = array('year' => $date[2],
                                          'month' => $date[1],
                                          'day' => $date[0],
                                          'hour' => $time[1][0],
                                          'min' => $time[1][1],
                                          'sec' => 0);                                            
                    
                    
                        $vevent = new vevent();
                        
                        $vevent->setProperty('dtstart', $startEvent);
                        $vevent->setProperty('dtend', $endEvent);

                        $vevent->setProperty('LOCATION', $entry['room']);

                        $vevent->setProperty('summary', $entry['description']);
                        $vevent->setProperty('description', $entry['class'] . ' - ' . $entry['description'] . ' (' . $entry['group'] . ')');
                        
                        if($entry['info'] != null) {
                            $vevent->setProperty('comment', $entry['info']);
                        }
                        
                        $vevent->setProperty('attendee', $entry['lector']);
                        $v->setComponent ($vevent);                        
                    }

                    echo $v->createCalendar();                    
                }
            } elseif($params['format'] == 'outlookCsv') {                
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
            
            if(!isset($params['class']) || empty($params['class'])) {
                $this->view->valid = false;
            } else {
                $this->view->valid = true;
                
                $week = Raumbelegung_Parser_Week::init($date);
                $week->setFilter(new Raumbelegung_Filter('class', $params['class']));             
                
                $weekData = $week->getData(); 
                $this->view->week = $weekData;
                $this->view->selectedClass = $params['class'];
            }

            $this->view->classes = $parser->getList('class');
            $this->view->date = $date;
            $this->view->title = 'Wochenübersicht KW ' . strftime('%V', strtotime($date));             
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
