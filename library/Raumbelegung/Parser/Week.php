<?php
class Raumbelegung_Parser_Week
{
	protected $_data = array();
    protected $_filters = array();
	protected $_startDate;
	protected $_curDate;
    protected $_days;
	
	public function __construct($startDate = null, $days = 7)
	{
        if($startDate == null)
            $startDate = strftime('%d.%m.%Y', time());
    
		$this->_startDate = $startDate;
		$this->_curDate = $startDate;
        $this->_days = $days;
	}
    
    public function setFilters($filters)
    {
        $this->_filters = $filters;
    }
    
    public function setFilter(Raumbelegung_Filter $filter)
    {
        if(!is_null($filter))
            $this->_filters[] = $filter;
    }
	
	protected function _fetchData()
	{
        $timestamp = strtotime($this->_curDate);
        
        $filters = array();
        $filters[] = new Raumbelegung_Filter('class', 'wi07-vz');
    
		for($i = 0; $i < $this->_days; $i++)
        {
            $date = strftime('%d.%m.%Y', $timestamp);
            $parser = new Raumbelegung_Parser($date);
            $parser->setCacheMode('week');
            $parser->setFilters($this->_filters);
            $this->_data[$date] = $parser->getData();
            
            $timestamp += 86400;
        }
	}
	
	public function getData()
	{
        $this->_fetchData();
		return $this->_data;
	}	
}