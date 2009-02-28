<?php
class Raumbelegung_Csv
{
    protected $_rows = array();
    
    public function addRow($row)
    {
        $this->_rows[] = $row;
        return $this;
    }
    
    public function toString()
    {
        $value = '';
        if(is_array($this->_rows) && count($this->_rows) > 0) {
            foreach($this->_rows as $row) {
                $value .= $row->toString() . "\n";
            }
        }
        
        return $value;
    }
}