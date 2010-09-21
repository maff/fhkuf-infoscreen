<?php
class InfoScreen_Validate_NoNn implements Zend_Validate_Interface
{
    public function getMessages()
    {
        return array();
    }

    public function isValid($value)
    {
        return(strpos($value, 'NN') === false);
    }
}