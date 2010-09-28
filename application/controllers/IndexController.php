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
        $request = InfoScreen_Model_Request::factory();

        $model = new InfoScreen_Model_Day($request->getDate());
        $model->setFilters($request->getFilters())->load();
        $this->view->model = $model;
    }

    public function weekAction()
    {
        $this->_handlePost('/week');
        $request = InfoScreen_Model_Request::factory();

        $model = new InfoScreen_Model_DaySpan_Week($request->getDate());
        $model->setFilters($request->getFilters())->load();
        $this->view->model = $model;
    }
}