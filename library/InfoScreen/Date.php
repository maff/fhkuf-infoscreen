<?php
class InfoScreen_Date
{
    public static $format = '%d.%m.%Y';

    public static function parse($date = null)
    {
        if(null === $date || empty($date)) {
            $date = strftime(self::$format, time());
        } else {
            $time = strtotime($date);
            if($time === false) {
                $date = strftime(self::$format, time());
            } else {
                $date = strftime(self::$format, $time);
            }
        }

        return $date;
    }

    public static function fromTime($time)
    {
        return strftime(self::$format, $time);
    }
}
