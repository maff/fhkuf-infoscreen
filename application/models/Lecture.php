<?php
class InfoScreen_Model_Lecture
{
    protected $_data;
    protected $_sealed = false;

    public function __construct(array $appointment)
    {
        foreach($appointment as $key => $value) {
            $this->__set($key, $value);
        }

        // Update lists
        foreach(InfoScreen_Model_List::getCollection() as $key => $list) {
            $list->add($this->__get($key));
        }

        $this->seal();
    }

    public function __get($key) {
        if(isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        return false;
    }

    public function __set($key, $value) {
        if($this->_sealed) {
            throw new Exception("Cannot set value on sealed instance");
        }

        $this->_data[$key] = trim($value);
    }

    public function seal()
    {
        $this->_sealed = true;
    }
}