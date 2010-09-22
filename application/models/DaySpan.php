<?php
class InfoScreen_Model_DaySpan extends InfoScreen_Model_DayCollection
{
    protected $_startDate;
    protected $_days;
    protected $_cacheType = 'month';

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

    public function getStartDate()
    {
        return $this->_startDate;
    }

    public function setDays($days)
    {
        $this->_days = (int) $days;
        return $this;
    }

    public function load()
    {
        $timestamp = strtotime($this->_startDate);
        $this->_data = array();

        for($i = 0; $i < $this->_days; $i++) {
            $date = strftime('%d.%m.%Y', $timestamp);
            $this->_addDate($date);
            $timestamp += 86400;
        }
    }
}