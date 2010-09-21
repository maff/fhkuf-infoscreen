<?php
class InfoScreen_Validate_NoSlash implements Zend_Validate_Interface
{
    public function getMessages()
    {
        return array();
    }

    public function isValid($value)
    {
        return (strpos($value, '/') === false);
    }
}