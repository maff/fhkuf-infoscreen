<?php
class IndexController extends InfoScreen_Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('/day');
    }

    public function dayAction()
    {
        if($this->getRequest()->isPost()) {
            $filterUrl = InfoScreen_Controller_Request::getFilterUrl();
            $filterUrl = (!empty($filterUrl)) ? '/' . $filterUrl : $filterUrl;
            $this->_redirect('/day' . $filterUrl);
        }

        $model = new InfoScreen_Model_Day(InfoScreen_Controller_Request::getDate());
        $model->setFilters(InfoScreen_Controller_Request::getFilters())->load();
        $this->view->model = $model;
    }

    public function weekAction()
    {
        if($this->getRequest()->isPost()) {
            $filterUrl = InfoScreen_Controller_Request::getFilterUrl();
            $filterUrl = (!empty($filterUrl)) ? '/' . $filterUrl : $filterUrl;
            $this->_redirect('/week' . $filterUrl);
        }

        $model = new InfoScreen_Model_Week(InfoScreen_Controller_Request::getDate());
        $model->setFilters(InfoScreen_Controller_Request::getFilters())->load();
        $this->view->model = $model;
    }
}