<?php
class JsController extends InfoScreen_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();

        $this->disableLayout();
        $this->_helper->viewRenderer->setViewSuffix('js');

        $this->getResponse()->setHeader('Content-type', 'text/javascript');
    }

    public function infoscreenAction()
    {
    }
}