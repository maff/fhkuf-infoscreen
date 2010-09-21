<?php
class InfoScreen_Model_DaySpan
{
    protected $_startDate;
    protected $_days;
    protected $_data;
    protected $_cacheType;
    protected $_filters = array();

    public function  __construct($startDate, $days)
    {
        $this->setStartDate($startDate);
        $this->setDays($days);
    }

    public function setStartDate($startDate)
    {
        $this->_startDate = strftime('%d.%m.%Y', strtotime($startDate));
        return $this;
    }

    public function setDays($days)
    {
        $this->_days = (int) $days;
        return $this;
    }

    /**
     * Set cache type
     *
     * @param  string $cacheType
     * @return InfoScreen_Model_DaySpan
     */
    public function setCacheType($cacheType)
    {
        $this->_cacheType = $cacheType;
        return $this;
    }

    /**
     * Set the filters to use
     *
     * @param array $filters
     * @return InfoScreen_Model_DaySpan
     */
    public function setFilters(array $filters)
    {
        $this->_filters = $filters;
        return $this;
    }

    /**
     * Add a filter
     *
     * @param InfoScreen_Model_Filter $filter
     * @return InfoScreen_Model_DaySpan
     */
    public function addFilter(InfoScreen_Model_Filter $filter)
    {
        $this->_filters[] = $filter;
        return $this;
    }

    public function fetch()
    {
        $timestamp = strtotime($this->_startDate);
        $this->_data = array();

        for($i = 0; $i < $this->_days; $i++) {
            $date = strftime('%d.%m.%Y', $timestamp);

            $day = new InfoScreen_Model_Day($date);
            $day->setCacheType($this->_cacheType);
            $day->setFilters($this->_filters);
            $this->_data[] = $day->load();

            $timestamp += 86400;
        }
    }

    public function getDays()
    {
        if($this->_data === null) {
            $this->fetch();
        }

        return $this->_data;
    }
}