<?php
class InfoScreen_Model_Filter_ClassAndYear extends InfoScreen_Model_Filter
{
    protected $_key = 'class';

    public function __construct($value)
    {
        $this->_value = trim($value);
    }

    public function isMatch(InfoScreen_Model_Lecture $lecture)
    {
        $result = true;

        if($this->_validate()) {
            $result = parent::isMatch($lecture);

            // Check for classes with year pattern (e.g. V2010)
            if($result === false) {
                if(preg_match('/([\d]{2})/', $this->_value, $match)) {
                    $year = 'V20' . $match[0];
                    if($lecture->__get($this->_key) == $year) {
                        $result = true;
                    }
                }
            }
        }

        return $result;
    }

}