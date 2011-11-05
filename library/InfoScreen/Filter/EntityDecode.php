<?php
class InfoScreen_Filter_EntityDecode implements Zend_Filter_Interface
{
    public function filter($value)
    {
        return mb_convert_encoding($value, 'UTF-8', 'HTML-ENTITIES');
    }
}