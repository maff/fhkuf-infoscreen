<?php
class InfoScreen_Model_DaySpan_Relative extends InfoScreen_Model_DaySpan
{
    protected $_days;
    protected $_startDate;
    protected $_cacheType = 'month';

    /**
     * Constructor
     *
     * @param string $date
     */
    public function __construct($date = null, $span = 14)
    {
        $date = $this->_parseDate($date);

        $this->setStartDate(strftime('%d.%m.%Y', strtotime($date . '-' . $span . 'days')));
        $this->setDays($span * 2 + 1);
    }
}