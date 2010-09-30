<?php
class InfoScreen_Statistics
{
    public static function track($pagetitle)
    {
        $config = InfoScreen_Config::getInstance();

        if($config->frontend->piwik->enabled) {
            $piwik = new PiwikTracker($config->frontend->piwik->siteid, 'http://' . $config->frontend->piwik->baseurl);
            $piwik->doTrackPageView($pagetitle);
        }
    }
}