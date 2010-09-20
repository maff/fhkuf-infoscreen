<?php
/**
 * Wrapper class for the InfoScreen Webservice
 */
class InfoScreen_Parser_Service
{
    /**
     * Get appointment list based on date and filters.
     *
     * @param string $date
     * @param array $filters
     * @return array
     */
    public function getResults($date = '', $filters = array())
    {
        $parser = new InfoScreen_Parser($date);
        
        if(count($filters) > 0)
            $parser->setFilters($filters);
            
        return $parser->getData();
    }
    
    /**
     * Get lists based on key.
     * 
     * @param string $key
     * @param bool $selectfriendly
     * @return array
     */
    public function getList($key, $selectfriendly = true)
    {
        $parser = new InfoScreen_Parser();
        return $parser->getList($key, $selectfriendly);
    }    
}