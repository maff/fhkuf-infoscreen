<?php
class InfoScreen_Model_Request
{
    protected $_date;
    protected $_filterValues = array();
    protected $_strict = true;

    protected $_type  = 'data';
    protected $_range = 'relative';
    protected $_key   = 'course';

    protected $_allowed = array(
        'type'  => array('data', 'list'),
        'range' => array('day', 'relative', 'weekend'),
        'key'   => array('course', 'lector', 'room')
    );

    protected static $_instances = array();

    public function __construct($options = array())
    {
        $this->_setOptions($options);
    }

    /**
     *
     * @return InfoScreen_Model_Request
     */
    public static function factory($type = 'controllerRequest')
    {
        $type = (string) $type;
        if(empty($type)) {
            throw new Exception('Invalid type');
        }

        if(isset(self::$_instances[$type])) {
            return self::$_instances[$type];
        }

        if($type == 'controllerRequest') {
            $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
            self::$_instances[$type] = new self($params);
            return self::$_instances[$type];
        }

        self::$_instances[$type] = new self();
        return self::$_instances[$type];
    }

    protected function _setOptions(array $options)
    {
        if(isset($options['date']) && $this->validateValue($options['date'])) {
            $this->_date = $options['date'];
        }

        if(isset($options['strict'])) {
            if($options['strict'] === false || $options['strict'] === 'false') {
                $this->_strict = false;
            }
        }

        foreach($this->_allowed['key'] as $key) {
            if(isset($options[$key]) && $this->validateValue($options[$key])) {
                $this->_filterValues[$key] = $options[$key];
            }
        }

        foreach($this->_allowed as $key => $allowed) {
            if(isset($options[$key])) {
                $this->_setOption($key, $options[$key], $allowed);
            }
        }
    }

    protected function _setOption($key, $value, array $allowed)
    {
        $member = '_' . $key;
        if(in_array($value, $allowed)) {
            $this->$member = $value;
        }
    }

    public function isStrict()
    {
        return $this->_strict;
    }

    public function validateValue($value)
    {
        return ($value !== null && !empty($value));
    }

    public function getAPIModel()
    {
        if($this->_type == 'list') {
            $model = InfoScreen_Model_List::getInstance($this->_key);
        } else {
            switch($this->_range) {
                case 'day':
                    $model = new InfoScreen_Model_DaySpan($this->_date, 1);
                    break;
                case 'weekend':
                    $model = new InfoScreen_Model_DaySpan_Weekend($this->_date);
                    break;
                default:
                    $model = new InfoScreen_Model_DaySpan_Relative($this->_date);
                    break;
            }

            $model->setFilters($this->getFilters())
                  ->load();
        }

        return $model;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getRange()
    {
        return $this->_range;
    }

    public function getKey()
    {
        return $this->_key;
    }

    public function getDateUrl($prefix = '/')
    {
        if($this->_date === null) {
            return '';
        }

        return $prefix . 'date/' . $this->_date;
    }

    public function getFilters()
    {
        $filters = array();
        if(count($this->_filterValues) > 0) {
            foreach($this->_filterValues as $key => $value) {
                if($key == 'course' && !$this->_strict) {
                    $filters[] = new InfoScreen_Model_Filter_CourseAndYear($value);
                } else {
                    $filters[] = new InfoScreen_Model_Filter($key, $value);
                }
            }
        }

        return $filters;
    }

    public function getFilterUrl($date = true, $prefix = '/')
    {
        $url = array();

        if($date && $this->_date != null) {
            $url[] = 'date';
            $url[] = $this->_date;
        }

        if(count($this->_filterValues) > 0) {
            foreach($this->_filterValues as $key => $value) {
                $url[] = $key;
                $url[] = $value;
            }
        }

        if(count($url) > 0) {
            return $this->completeUrl($prefix . implode('/', $url));
        }

        return $this->completeUrl('');
    }

    public function completeUrl($url)
    {
        if($this->_strict === false && strpos($url, 'strict') === false) {
            $url .= '/strict/false';
        }

        return $url;
    }
}