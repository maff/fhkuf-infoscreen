<?php
require_once('simple_html_dom.php');

class Raumbelegung_Parser
{
    protected $_date;
    protected $_data;
    protected $_filters;
    protected $_url;
    protected $_lists;
    
    public function __construct($date = '')
    {
        $this->_setDate($date);
        $this->_setUrl();
        $this->_loadLists();
    }
    
    public function __destruct()
    {
        $this->_saveLists();
    }
    
    public function getDate()
    {
        return $this->_date;
    }
    
    public function getData()
    {
        $this->_fetchData();
        $this->_manageLists();
        $this->_filter();
        return $this->_data;        
    }
    
    public function getList($key, $selectfriendly = true)
    {
        if(isset($this->_lists[$key]))
        {
            if(!$selectfriendly)
                return array_slice($this->_lists[$key], 1);
            else
                return $this->_lists[$key];
        }
        
        return false;
    }
    
    public function setFilters($filters)
    {
		if(is_array($filters) && count($filters) > 0)
		{
			foreach($filters as $filter)
			{
				$this->_filters[$filter->key] = $filter->value;
			}
		}
    }
    
    protected function _setDate($date)
    {
        if(empty($date))
            $this->_date = strftime('%d.%m.%Y', time());
        else
            $this->_date = strftime('%d.%m.%Y', strtotime($date));
            
        Zend_Registry::getInstance()->set('parser_date', $this->_date);
    }
    
    protected function _setUrl()
    {
        $this->_url = Raumbelegung_Config::get('infoscreen_url');
        $this->_url .= '?STGID=' . Raumbelegung_Config::get('infoscreen_stgid');
        $this->_url .= '&DATUM=' . $this->_date;
    }
    
    /**
     * _getUrlHash
     * 
     * Returns URL hash as identification key for caching.
     * 
     * @return string
     */
    protected function _getUrlHash()
    {
        return md5($this->_url);
    }
    
    
    /**
     * _fetchData
     * 
     * Fetches Data and caches it using Zend_Cache
     *
     */
    private function _fetchData()
    {
        $frontendOptions = array(
           'lifetime' => Raumbelegung_Config::get('data_cache_lifetime'),
           'automatic_serialization' => true
        );
        
        $backendOptions = array(
            'cache_dir' => self::_getCacheDir()
        );
        
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        if(!$data = $cache->load($this->_getUrlHash()))
        {
            $data = $this->_parseURL();
            $cache->save($data, $this->_getUrlHash());
        }
        
        $this->_data = $data;
    }
    
    private function _filter()
    { 
        if($this->_checkData() && $this->_checkFilters())
		{
		    $tmpdata = array();
			foreach($this->_data as &$item)
			{
				$ins = true;
				foreach($item as $key => $value)
				{					
					if(isset($this->_filters[$key]) && !empty($this->_filters[$key])
						&& (strpos(self::_cleanCheck($item[$key]), self::_cleanCheck($this->_filters[$key])) === false))
							$ins = false;					
				}
				
				if($ins) $tmpdata[] = $item;				
			}
			$this->_data = $tmpdata;
		}
    }
    
    /**
     * _parseURL
     * 
     * Parses the requested URL using Simple HTML DOM
     *
     */
    private function _parseURL()
    {
        $data = array();
        $dom = file_get_dom($this->_url);
		foreach($dom->find('div.appointment') as $element)
		{			
			$info = array();
			$info['startTime'] = @$element->find('td.appointmentDate', 0)->plaintext;
			$info['endTime'] = @$element->find('td.appointmentDate', 1)->plaintext;
			$info['class'] = @$element->find('td.appointmentDate', 2)->plaintext;
			$info['description'] = @$element->find('span.appointmentText', 0)->plaintext;
			$info['lector'] = @$element->find('span.appointmentLektor', 0)->plaintext;
			$info['room'] = @$element->find('td.appointmentRaum > div', 0)->plaintext;
			$info['info'] = @$element->find('div.appointmentInfo span', 0)->plaintext;
			
			$data[] = $info;
		}
		
		return $data;
    }
    
    private function _manageLists()
    {
        if($this->_checkData())
        {
            foreach($this->_data as $entry)
            {
                foreach(array('class', 'room', 'lector') as $key)
                {
                    $this->_addListItem($key, $entry[$key]);
                }
            }
            
            foreach($this->_lists as $key => &$list)
                sort($list);
        }
    }
    
    private function _addListItem($key, $value)
    {
        if(!in_array($value, $this->_lists[$key]) && self::checkCacheItem($value)) $this->_lists[$key][] = $value;
    }
    
    private function _getCacheFile($key)
    {
        return self::_getCacheDir() . '/' . $key;
    }
    
    private static function _getCacheDir()
    {
        return Zend_Registry::getInstance()->get('base_path') . '/' . Raumbelegung_Config::get('cache_dir');
    }
    
    private function _loadLists()
    {
    	$this->_lists = array(
    		'class' => array(''),
    		'lector' => array(''),
    		'room' => array('')
    	);
    	
    	foreach($this->_lists as $key => $list)
    	{
    	    $cachefile = $this->_getCacheFile($key);
    	    if(file_exists($cachefile))
    	    {
    	        $cache = unserialize(file_get_contents($cachefile));
    	        if(is_array($cache) && count($cache) > 0)
    	        {
                    $this->_lists[$key] = $cache;
    	        }
    	    }    	    
    	}
    }
    
    private function _saveLists()
    {
        foreach($this->_lists as $key => $list)
        {
           $cachefile = $this->_getCacheFile($key);
            file_put_contents($cachefile, serialize($list));
        }
    }
    
	private function _checkData()
	{
		if(is_array($this->_data) && count($this->_data) > 0) return true;
		return false;
	}
    
    private function _checkFilters()
    {
        if(is_array($this->_filters) && count($this->_filters) > 0) return true;
        return false;
    }
	
	private static function _cleanCheck($value)
	{
		$value = trim(strtolower($value));
	
		if($value == 'wi07') $value = 'wi07-vz';
		if($value == 'wi06') $value = 'wi06-vz';
	
		return $value;
	}
	
    /**
     * Checks if item should be cached (excludes lists, NN, etc.)
     *
     * @param string $value
     * @return bool
     */
    public static function checkCacheItem($value)
	{
		$cache = self::checkFilterLink($value);
		
        // don't cache on multiple values
		if(strpos($value, ',') !== false)
			$cache = false;
	
		return $cache;
	}

    public static function checkFilterLink($value)
    {
        $cache = true;
        
        if(empty($value))
			$cache = false;
    
		if(strpos($value, '/') !== false)
			$cache = false;
		
        // don't cache on NN value (undefined)
		if(strpos($value, 'NN') !== false)
		    $cache = false;
        
        // don't cache without having any letters or numbers
        if(!preg_match('/[a-zA-Z0-9]+/i', $value))
            $cache = false;
            
        return $cache;
    }
}
