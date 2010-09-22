<?php
class InfoScreen_Model_DaySpan_Month extends InfoScreen_Model_DaySpan
{
    protected $_days;
    protected $_startDate;
    protected $_cacheType = 'month';

    /**
     * Constructor
     *
     * @param string $date
     */
    public function __construct($date = null)
    {
        $date = $this->_parseDate($date);

        $timestamp = strtotime($date);

        $this->setStartDate(strftime('01.%m.%Y', $timestamp));
        $this->setDays(date('t', $timestamp));
    }
}