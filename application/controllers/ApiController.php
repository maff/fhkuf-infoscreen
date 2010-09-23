<?php
class ApiController extends InfoScreen_Controller_Action
{
    public function  preDispatch()
    {
        parent::preDispatch();

        if(in_array($this->getRequest()->getActionName(), array('json', 'xml', 'ical'))) {
            if($this->getRequest()->getActionName() == 'ical' && $this->_getType() == 'list') {
                throw new Zend_Controller_Action_Exception('Not found.', 404);
            }

            $this->disableLayout();

            if($this->getRequest()->getParam('categorized', false) === 'true') {
                $this->view->categorized = true;
            }

            if($this->_getType() == 'list') {
                $this->_helper->viewRenderer->setViewScriptPathSpec(':controller/list/:action.:suffix');
            }
        }
    }

    protected function _getModel()
    {
        if($this->_getType() == 'list') {
            $model = InfoScreen_Model_List::getInstance($this->_getKey());
        } else {
            $date = InfoScreen_Controller_Request::getDate();

            switch($this->_getRange()) {
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
        }

        return $model;
    }

    protected function _getType()
    {
        return $this->_getRequestValue('type', array('data', 'list'), 'data');
    }

    protected function _getRange()
    {
        return $this->_getRequestValue('range', array('day', 'relative', 'weekend'), 'relative');
    }

    protected function _getKey()
    {
        return $this->_getRequestValue('key', array('course', 'lector', 'room'), 'course');
    }

    protected function _getRequestValue($key, array $allowed, $default)
    {
        $value = $this->getRequest()->getParam($key, false);

        if(in_array($value, $allowed)) {
            return $value;
        }

        return $default;
    }

    public function indexAction()
    {
    }

    public function generatorAction()
    {
    }

    public function jsonAction()
    {
        $this->_handlePost('/api/json');
        $this->view->model = $this->_getModel();

        if($this->isDebug()) {
            $this->getResponse()->setHeader('Content-type', 'text/plain', true);
        } else {
            $this->getResponse()->setHeader('Content-type', 'application/json', true);
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename="api.json"', true);
        }
    }

    public function icalAction()
    {
        $this->_handlePost('/api/ical');
        $this->view->model = $this->_getModel();

        if($this->isDebug()) {
            $this->getResponse()->setHeader('Content-type', 'text/plain', true);
        } else {
            $this->getResponse()->setHeader('Content-type', 'text/calendar', true);
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename="api.ical"', true);
        }
    }

    public function xmlAction()
    {
        $this->_handlePost('/api/xml');
        $this->view->model = $this->_getModel();

        $this->getResponse()->setHeader('Content-type', 'text/xml', true);
    }
}