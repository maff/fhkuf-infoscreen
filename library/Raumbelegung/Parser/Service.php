<?php
class Raumbelegung_Parser_Service_Abstract
{
    /**
     * Get appointment list based on date and filters.
     *
     * @param string $date
     * @param array $filters
     * @return array
     */
    public function getResults($date ='', $filters = array())
    {
        $parser = new Raumbelegung_Parser($date);
        if(count($filters) > 0)
            $parser->setFilters($filters);
            
        return $parser->getData();
    }
    
    /**
     * Get lists based on key.
     *
     * @param string $key
     * @return array
     */
    public function getList($key)
    {
        $parser = new Raumbelegung_Parser();
        return $parser->getList($key);
    }    
}