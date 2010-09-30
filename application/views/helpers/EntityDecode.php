<?php
/**
 * EntityDecode helper
 *
 * @uses helper Zend_View_Helper
 */
class Zend_View_Helper_EntityDecode
{
    function entityDecode($value)
    {    
        $replacements = array(
            '&amp;' => '&'
        );
        
        foreach($replacements as $search => $replace) {
            $value = str_replace($search, $replace, $value); 
        }
        
        return $value;
    }    
}