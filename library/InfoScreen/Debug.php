<?php
class InfoScreen_Debug extends Zend_Debug
{
    public static function dumpPlain($var, $echo = true)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        if($echo) {
            echo $output;
            return;
        }

        return $output;
    }
}