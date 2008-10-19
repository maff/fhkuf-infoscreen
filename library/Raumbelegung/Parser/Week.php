<?php
class Raumbelegung_Parser_Week
{
    public static function init($date)
    {
        return new Raumbelegung_Parser_Overview(self::_getStartDate($date), 6);
    }
    
    protected static function _getStartDate($date)
    {
        $timestamp = strtotime($date);
        $weekStart = mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) - date('w', $timestamp) + 1, date('Y', $timestamp));
        
        return strftime('%d.%m.%Y', $weekStart);
    }
}