<?php
class InfoScreen_Model_List implements Countable
{
    /**
     * List instances
     *
     * @var array
     */
    protected static $_instances = array();

    /**
     * Cache key
     * 
     * @var string
     */
    protected $_cacheKey;

    /**
     * Cache instance
     *
     * @var Zend_Cache_Core
     */
    protected $_cache;

    /**
     * List data
     *
     * @var array
     */
    protected $_data = array();

    /**
     * If data was modified, persist new data to cache
     *
     * @var bool
     */
    protected $_modified = false;

    /**
     * Validators to check the list value
     *
     * @var array
     */
    protected $_validators = array();

    /**
     * Filters to apply on list value
     *
     * @var array
     */
    protected $_filters = array();

    /**
     * Get a list instance
     * 
     * @param  string $cacheKey
     * @return InfoScreen_Model_List
     */
    public static function getInstance($cacheKey)
    {
        if(!isset(self::$_instances[$cacheKey])) {
            self::$_instances[$cacheKey] = new self($cacheKey);
        }
        
        return self::$_instances[$cacheKey];
    }

    public static function getCollection()
    {
        return array(
            'course' => InfoScreen_Model_List::getInstance('course'),
            'lector' => InfoScreen_Model_List::getInstance('lector'),
            'room'   => InfoScreen_Model_List::getInstance('room')
        );
    }

    private function __construct($cacheKey)
    {
        $this->setCacheKey($cacheKey);

        $this->_addDefaultFilters();
        $this->_addDefaultValidators();

        $this->load();
    }

    private final function  __clone()
    {
    }

    public function setCacheKey($cacheKey)
    {
        if(!is_string($cacheKey) || empty($cacheKey)) {
            throw new Exception('cacheKey must be a valid identifier');
        }

        $this->_cacheKey = $cacheKey;
        return $this;
    }

    public function add($value)
    {
        $value = $this->filter($value);

        if($this->validate($value) && !$this->contains($value, false)) {
            $this->_modified = true;
            $this->_data[] = $value;
            $this->sort();
        }

        return $this;
    }

    public function contains($value, $filter = true)
    {
        if($filter) {
            $value = $this->filter($value);
        }

        if($this->validate($value) && in_array($value, $this->_data)) {
            return true;
        }

        return false;
    }

    /**
     * Implement Countable
     *
     * @return int
     */
    public function count()
    {
        return count($this->_data);
    }

    public function sort()
    {
        sort($this->_data);
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function getSelectData()
    {
        return array_merge(array(''), $this->getData());
    }

    public function addFilter(Zend_Filter_Interface $filter)
    {
        $this->_filters[] = $filter;
        return $this;
    }

    public function filter($value)
    {
        if(count($this->_filters) > 0) {
            foreach($this->_filters as $filter) {
                $value = $filter->filter($value);
            }
        }

        return $value;
    }

    public function addValidator(Zend_Validate_Interface $validator)
    {
        $this->_validators[] = $validator;
        return $this;
    }

    public function validate($value, $checkMultiple = true)
    {
        $valid = true;

        if($checkMultiple && strpos($value, ',') !== false) {
            return false;
        }

        if(count($this->_validators) > 0) {
            foreach($this->_validators as $validator) {
                if(!$validator->isValid($value)) {
                    $valid = false;
                    break;
                }
            }
        }

        return $valid;
    }

    protected function _addDefaultFilters()
    {
        $this->addFilter(new Zend_Filter_StringTrim());
    }

    protected function _addDefaultValidators()
    {
        $this->addValidator(new Zend_Validate_NotEmpty());
        $this->addValidator(new InfoScreen_Validate_HasAlnum());
        $this->addValidator(new InfoScreen_Validate_NoSlash());
        $this->addValidator(new InfoScreen_Validate_NoNn());
    }

    /**
     * Get the cache instance
     *
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return InfoScreen_Cache::get('list');
    }

    public function load()
    {
        if($data = $this->getCache()->load($this->_cacheKey)) {
            if(is_array($data) && count($data) > 0) {
                foreach($data as $value) {
                    $this->add($value);
                }
            }
        }

        return $this;
    }

    /**
     * Save list contents to cache
     */
    public function persist()
    {
        $this->getCache()->save($this->_data, $this->_cacheKey);
        return $this;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if($this->_modified === true) {
            $this->persist();
        }
    }
}