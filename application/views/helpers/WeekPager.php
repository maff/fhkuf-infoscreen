<?php
class Zend_View_Helper_WeekPager
{
    public function weekPager($date, $mode)
    {
        switch($mode)
        {
            case 'prev':
                $relative = '-1week';
                break;
            case 'next':
                $relative = '+1week';
                break;                            
        }
        
        $resTime = strtotime($relative, strtotime($date));
        return strftime('%d.%m.%Y', $resTime);
    }
}