<?php
class Zend_View_Helper_DayPager
{
    public function dayPager($date, $mode)
    {
        switch($mode)
        {
            case 'prev':
                $relative = '-1day';
                break;
            case 'next':
                $relative = '+1day';
                break;                            
        }
        
        $resTime = strtotime($relative, strtotime($date));
        if(strftime('%u', $resTime) == 7) {
            $resTime = strtotime($relative, $resTime);
        }

        return InfoScreen_Date::fromTime($resTime);
    }
}