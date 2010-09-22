<?php
abstract class InfoScreen_Model_DayCollection
{
    protected $_data;
    protected $_cacheType;
    protected $_filters = array();

    /**
     * Set a correct date
     *
     * @param  string $date
     * @return string
     */
    protected function _parseDate($date = null)
    {
        if(null === $date || empty($date)) {
            $date = strftime('%d.%m.%Y', time());
        } else {
            $date = strftime('%d.%m.%Y', strtotime($date));
        }

        return $date;
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

    protected function _addDate($date)
    {
        $day = new InfoScreen_Model_Day($date);
        $day->setCacheType($this->_cacheType);
        $day->setFilters($this->_filters);
        $this->_data[] = $day->load();
    }
    
    public function load()
    {
    }

    public function getData()
    {
        if($this->_data === null) {
            $this->load();
        }

        return $this->_data;
    }

    public function hasData()
    {
        /* @var $day InfoScreen_Model_Day */
        foreach($this->getData() as $day) {
            if($day->hasData()) {
                return true;
            }
        }

        return false;
    }

    public function toArray($categorized = false)
    {
        $array = array();

        /* @var $day InfoScreen_Model_Day */
        foreach($this->getData() as $day) {
            if($categorized) {
                $array[$day->getDate()] = $day->toArray();
            } else {
                $array[] = $day->toArray();
            }
        }

        return $array;
    }
}