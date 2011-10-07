<?php
require_once realpath(dirname(__FILE__) . '/../../application/Setup.php');

// Load Zend_Application
$application = Application_Setup::app();

// Bootstrapping resources
$bootstrap = $application->bootstrap();

// Console
$cli = new \Symfony\Component\Console\Application(
    'InfoScreen Command Line Interface',
    \InfoScreen_Version::VERSION
);

$cli->setCatchExceptions(true);

$cli->addCommands(array(
    // Cache Commands
    new \InfoScreen\Tools\Console\Command\Cache\ClearCommand(),
));

$cli->run();