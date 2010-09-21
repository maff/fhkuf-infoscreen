<?php
class ErrorController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        // $this->_helper->layout()->disableLayout(); 
        // Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
    }

    public function errorAction()
    {
        $error = $this->_getParam('error_handler');
        if(!$error instanceof ArrayObject)
        {
            $error = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
            
            $params = $this->getRequest()->getParams();
            if($params['controller'] == 'error' && $params['action'] == 'error')
                $error->type = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER;
            else
                $error->type = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER;
        }
        
        switch ($error->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                
                $this->view->title = '404 Not found';
                $this->view->content = 'The page you requested could not be found.';

                break;
                
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
            case InfoScreen_Error_Handler::EXCEPTION_CATCHED_EXCEPTION:
            default:
            
                $this->getResponse()->setRawHeader('HTTP/1.1 500 Internal Server Error');
                
                $this->view->title = '500 Internal Error';
                $this->view->content = 'An internal error ocurred. Please try again later.';
                
                break;
        }

        if(APPLICATION_ENV == 'development') {
            $this->view->content .= Zend_Debug::dump($error, null, false);
        }

        $this->getResponse()->clearBody();
    }
}
