<?php
class InfoScreen_Validate_HasAlnum implements Zend_Validate_Interface
{
    public function getMessages()
    {
        return array();
    }

    public function  isValid($value)
    {
        return (preg_match('/[a-zA-Z0-9]+/i', $value));
    }
}