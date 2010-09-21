<?php
class InfoScreen_Model_Week extends InfoScreen_Model_DaySpan
{
    protected $_days = 6;
    protected $_startDate;
    protected $_cacheType = 'week';

    /**
     * Constructor
     *
     * @param string $startDate
     */
    public function __construct($date = null)
    {
        $this->_setDate($date);
    }

    protected function _setDate($date = null)
    {
        if(null === $date || empty($date)) {
            $date = strftime('%d.%m.%Y', time());
        } else {
            $date = strftime('%d.%m.%Y', strtotime($date));
        }

        $timestamp = strtotime($date);
        $weekStart = mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) - date('w', $timestamp) + 1, date('Y', $timestamp));

        $this->setStartDate(strftime('%d.%m.%Y', $weekStart));
    }
}