<?php
class InfoScreen_Model_DaySpan_Weekend extends InfoScreen_Model_DayCollection
{
    protected $_date;
    protected $_amount;
    protected $_cacheType = 'month';

    protected $_startDay = 5; // Fri
    protected $_offset =   1; // Sat

    /**
     * Constructor
     *
     * @param string $date
     */
    public function __construct($date = null, $amount = 4)
    {
        $this->setDate($date);
        $this->setAmount($amount);
    }
    
    public function setDate($date = null)
    {
        $this->_date = $this->_parseDate($date);
        return $this;
    }

    public function setAmount($amount)
    {
        $this->_amount = (int) $amount;
        return $this;
    }

    public function getDates()
    {
        $currentWeekday = strftime('%w', strtotime($this->_date));
        $diff = $this->_startDay - $currentWeekday;

        $prefix = '+';
        if($diff < 0) {
            $prefix = '-';
        }
        
        $calculation = $this->_date . $prefix . abs($diff) . 'days';
        $currentStartDay = InfoScreen_Date::parse($calculation);

        $startDay = InfoScreen_Date::parse($currentStartDay . '-' . $this->_amount . 'weeks');

        // amount in past and future + current week
        $weekends = $this->_amount * 2 + 1;
        $weekStartDay = $startDay;

        $dates = array();
        for($i = 0; $i < $weekends; $i++) {
            $currentDay = $weekStartDay;

            for($j = 0; $j <= $this->_offset; $j++) {
                $dates[] = $currentDay;
                $currentDay = InfoScreen_Date::parse($currentDay . '+1day');
            }

            $weekStartDay = InfoScreen_Date::parse($weekStartDay . '+1week');
        }

        return $dates;
    }

    public function load()
    {
        foreach($this->getDates() as $date) {
            $this->_addDate($date);
        }
    }
}