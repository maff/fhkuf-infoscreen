<?php
class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 Fehler -- Kontroller oder Aktion nicht gefunden
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');

                // ... Ausgabe für die Anzeige erzeugen...
                break;
            default:
                // Anwendungsfehler; Fehler Seite anzeigen, aber den Status Code nicht ändern
                break;
        }
    }
}