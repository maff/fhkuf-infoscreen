<?php
class ErrorController extends Zend_Controller_Action
{
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
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:

                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                
                $this->view->title = '404 Not found';
                $this->view->content = 'The page you requested could not be found.';

                $this->_logErrorInfo($error, 'debug');

                break;
                
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
            default:
            
                $this->getResponse()->setRawHeader('HTTP/1.1 500 Internal Server Error');
                
                $this->view->title = '500 Internal Error';
                $this->view->content = 'An internal error ocurred. Please try again later.';

                $this->_logErrorInfo($error, 'err');

                break;
        }

        if(APPLICATION_ENV == 'development') {
            $this->view->content .= Zend_Debug::dump($this->_getErrorInfo($error), null, false);
        }

        $this->getResponse()->clearBody();
    }

    protected function _logErrorInfo($error, $level = 'err')
    {
        $log = InfoScreen_Log::getInstance();
        foreach($this->_getErrorInfo($error) as $line) {
            $log->$level(InfoScreen_Debug::dumpPlain($line, false));
        }
    }

    protected function _getErrorInfo($error)
    {
        $exception = $error->exception;

        $info = array();
        $info[] = $exception->getMessage();
        $info[] = $this->getRequest()->getParams();
        $info[] = $exception->getTrace();

        return $info;
    }
}