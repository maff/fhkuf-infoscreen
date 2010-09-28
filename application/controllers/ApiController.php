<?php
class ApiController extends InfoScreen_Controller_Action
{
    /**
     * @var InfoScreen_Model_Request
     */
    protected $_requestModel;

    public function  preDispatch()
    {
        parent::preDispatch();

        if(in_array($this->getRequest()->getActionName(), array('json', 'xml', 'ical'))) {
            if($this->getRequest()->getActionName() == 'ical' && $this->_getType() == 'list') {
                throw new Zend_Controller_Action_Exception('Not found.', 404);
            }

            $this->_requestModel = InfoScreen_Model_Request::factory();
            $this->disableLayout();

            if($this->getRequest()->getParam('categorized', false) === 'true') {
                $this->view->categorized = true;
            }

            if($this->_requestModel->getType() == 'list') {
                $this->_helper->viewRenderer->setViewScriptPathSpec(':controller/list/:action.:suffix');
            }

            $this->view->model = $this->_requestModel->getAPIModel();
        }
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

        $this->getResponse()->setHeader('Content-type', 'text/xml', true);
    }
}