<?php
class InfoScreen_Model_Day
{
    /**
     * Type of cache to use (day|week)
     * 
     * @var string
     */
    protected $_cacheType = 'day';

    /**
     * Fetched data
     *
     * @var array
     */
    protected $_data;

    /**
     * Is data loaded?
     *
     * @var bool
     */
    protected $_isLoaded = false;

    /**
     * Filters
     *
     * @var array
     */
    protected $_filters = array();

    /**
     * Constructor
     *
     * @param string $date
     */
    public function __construct($date = null)
    {
        $this->setDate($date);
    }

    /**
     * Set cache type
     *
     * @param string $cacheType
     */
    public function setCacheType($cacheType)
    {
        $this->_cacheType = $cacheType;
    }

    /**
     * Set the used date
     *
     * @param string $date
     */
    public function setDate($date)
    {
        $this->_date = InfoScreen_Date::parse($date);
    }

    /**
     * Get the used date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Set the filters to use
     *
     * @param array $filters
     * @return InfoScreen_Model_Day
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
     * @return InfoScreen_Model_Day
     */
    public function addFilter(InfoScreen_Model_Filter $filter)
    {
        $this->_filters[] = $filter;
        return $this;
    }

    /**
     * Get the cache instance
     *
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return InfoScreen_Cache::get('data_' . $this->_cacheType);
    }

    /**
     * Cache key
     *
     * @return string
     */
    public function getCacheKey()
    {
        return str_replace('.', '_', $this->getDate());
    }

    /**
     * Load and parse data
     *
     * @return InfoScreen_Model_Day
     */
    public function load()
    {
        if(!$data = $this->getCache()->load($this->getCacheKey()))
        {
            $parser = new InfoScreen_Model_Parser($this->_date);
            $data = $parser->getData();

            $this->getCache()->save($data, $this->getCacheKey());
        }

        $this->_data = array();
        if(count($data) > 0) {
            foreach ($data as $lectureData) {
                $this->_data[] = new InfoScreen_Model_Lecture($lectureData);
            }
        }

        $this->_isLoaded = true;
        return $this;
    }

    /**
     * Get the fetched data
     *
     * @return array
     */
    public function getData()
    {
        if(!$this->_isLoaded) {
            $this->load();
        }

        return $this->_data;
    }

    /**
     * Get filtered data
     *
     * @return array
     */
    public function getFilteredData()
    {
        $data = $this->getData();

        if(count($this->_filters) == 0) {
            return $data;
        }

        $filtered = array();
        foreach($data as $lecture) {
            $include = true;
            foreach ($this->_filters as $filter) {
                if(!$filter->isMatch($lecture)) {
                    $include = false;
                }
            }

            if($include) {
                $filtered[] = $lecture;
            }
        }

        return $filtered;
    }

    public function hasData()
    {
        if(count($this->getFilteredData()) > 0) {
            return true;
        }

        return false;
    }

    public function toArray()
    {
        $array = array();

        /* @var $lecture InfoScreen_Model_Lecture */
        foreach($this->getFilteredData() as $lecture) {
            $array[] = $lecture->toArray();
        }

        return $array;
    }
}