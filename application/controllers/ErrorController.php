<?php
class ErrorController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout(); 
        Zend_Controller_Front::getInstance()->setParam('noViewRenderer', true);
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 Fehler -- Kontroller oder Aktion nicht gefunden
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');

                $content =<<<EOH
<h3>Error!</h3>
<p>Die angefragte Seite konnte nicht gefunden werden.</p>
EOH;
                break;
            default:
                // Anwendungsfehler
                $content =<<<EOH
<h1>Fehler!</h1>
<p>Ein unerwarteter Fehler trat auf. Bitte versuchen Sie es etwas später nocheinmal.</p>
EOH;
                break;
        }

        // Vorherige Inhalte löschen
        $this->getResponse()->clearBody();

        $this->view->content = $content;
        
        echo $content;
    }
}
