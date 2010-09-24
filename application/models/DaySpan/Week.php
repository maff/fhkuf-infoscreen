<?php
class InfoScreen_Model_DaySpan_Week extends InfoScreen_Model_DaySpan
{
    protected $_days = 6;
    protected $_startDate;
    protected $_cacheType = 'week';

    /**
     * Constructor
     *
     * @param string $date
     */
    public function __construct($date = null)
    {
        $date = $this->_parseDate($date);

        $timestamp = strtotime($date);
        $weekStart = mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) - date('w', $timestamp) + 1, date('Y', $timestamp));

        $this->setStartDate(InfoScreen_Date::fromTime($weekStart));
    }
}