<?php
class InfoScreen_Model_Filter
{
    protected $_key;
    protected $_value;

    public function __construct($key, $value)
    {
        $this->_key = trim($key);
        $this->_value = trim($value);
    }
    
    protected function _validate()
    {
        if(!empty($this->_key) && !empty($this->_value)) {
            return true;
        }
        
        return false;
    }

    public function isMatch(InfoScreen_Model_Lecture $lecture)
    {
        if($this->_validate()) {
            if(!$this->_check($lecture->__get($this->_key))) {
                return false;
            }
        }

        return true;
    }

    protected function _check($value)
    {
        $sanitize = new Zend_Filter();
        $sanitize->addFilter(new Zend_Filter_StringTrim());
        $sanitize->addFilter(new Zend_Filter_StringToLower());

        $filterValue = $sanitize->filter($this->_value);
        $dataValue = $sanitize->filter($value);

        if(strpos($dataValue, $filterValue) === false) {
            return false;
        }

        return true;
    }
}