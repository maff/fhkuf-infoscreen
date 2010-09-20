<?php
class Raumbelegung_Parser_Overview
{
    // wheter to return an array organized by date or not
    public $categorizeResults = true;

	protected $_data = array();
    protected $_filters = array();
	protected $_startDate;
	protected $_curDate;
    protected $_days;
    
    protected $_parser;
	
	public function __construct($startDate = null, $days)
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
            
            if($this->categorizeResults)
                $this->_data[$date] = $parser->getData();
            else {
                $this->_data = array_merge($this->_data, $parser->getData());
            }
            
            $timestamp += 86400;
        }
        
        $this->_parser = $parser;
	}
	
	public function getData()
	{
        $this->_fetchData();
		return $this->_data;
	}
    
    public function getParser()
    {
        return $this->_parser;
    }
}