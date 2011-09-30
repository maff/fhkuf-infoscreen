<?php
class InfoScreen_Controller_Plugin_Down extends Zend_Controller_Plugin_Abstract
{
    /**
     * Redirect to site down action
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if(!($request->controller == 'down' && $request->action == 'index')) {
            /* @var $redirector Zend_Controller_Action_Helper_Redirector */
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->goToUrl('/down');
        }
    }
}