<?php
class IndexController extends InfoScreen_Controller_Action
{
    public function  preDispatch()
    {
        parent::preDispatch();
        $this->view->mode = $this->getRequest()->getActionName();
    }

    public function indexAction()
    {
        $this->_redirect('/day');
    }

    public function dayAction()
    {
        $this->_handlePost('/day');

        $model = new InfoScreen_Model_Day(InfoScreen_Controller_Request::getDate());
        $model->setFilters(InfoScreen_Controller_Request::getFilters())->load();
        $this->view->model = $model;
    }

    public function weekAction()
    {
        $this->_handlePost('/week');

        $model = new InfoScreen_Model_DaySpan_Week(InfoScreen_Controller_Request::getDate());
        $model->setFilters(InfoScreen_Controller_Request::getFilters())->load();
        $this->view->model = $model;
    }
}