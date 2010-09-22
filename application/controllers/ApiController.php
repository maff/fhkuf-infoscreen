<?php
class ApiController extends InfoScreen_Controller_Action
{
    public function  preDispatch()
    {
        parent::preDispatch();
        $this->disableLayout();
        
        if($this->getRequest()->getParam('categorized', false) === 'true') {
            $this->view->categorized = true;
        }
    }

    protected function _getModel()
    {
        $date = InfoScreen_Controller_Request::getDate();

        switch($this->_getType()) {
            case 'day':
                $model = new InfoScreen_Model_DaySpan($date, 1);
                break;
            case 'weekend':
                $model = new InfoScreen_Model_DaySpan_Weekend($date);
                break;
            default:
                $model = new InfoScreen_Model_DaySpan_Relative($date);
                break;
        }

        $model->setFilters(InfoScreen_Controller_Request::getFilters())
              ->load();
        
        return $model;
    }

    protected function _getType()
    {
        $type = $this->getRequest()->getParam('type', false);

        if(in_array($type, array('day', 'relative', 'weekend'))) {
            return $type;
        }

        return 'relative';
    }

    public function indexAction()
    {
        $this->_redirect('/api/json');
    }

    public function jsonAction()
    {
        $this->_handlePost('/api/json');
        $this->view->model = $this->_getModel();

        $this->getResponse()->setHeader('Content-type', 'application/json');
    }

    public function icalAction()
    {
        $this->_handlePost('/api/ical');
        $this->view->model = $this->_getModel();

        $this->getResponse()->setHeader('Content-type', 'text/calendar');
    }

    public function xmlAction()
    {
        $this->_handlePost('/api/xml');
        $this->view->model = $this->_getModel();

        $this->getResponse()->setHeader('Content-type', 'text/xml');
    }
}