<?php
require_once realpath(dirname(__FILE__) . '/../application/Setup.php');

$application = Application_Setup::app();
$application->bootstrap()
            ->run();