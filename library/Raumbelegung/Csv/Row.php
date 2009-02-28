<?php
class Raumbelegung_Csv_Row
{
    protected $_columns = array();
    
    public function addColumn($value)
    {
        $this->_columns[] = $value;
        return $this;
    }
    
    public function toString()
    {
        $value = '';
        if(is_array($this->_columns) && count($this->_columns) > 0) {
            foreach($this->_columns as &$column) {
                if($column !== false)
                    $column = '"' . $column . '"';
            }
            
            $value = implode(',', $this->_columns);
        }
        
        return $value;
    }
}