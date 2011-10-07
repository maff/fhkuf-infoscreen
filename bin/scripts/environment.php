<?php
$scriptName = basename($argv[0]);

// set environment based on filename
$nameParts = explode('-', $scriptName);
if(count($nameParts) > 1) {
    if(in_array($nameParts[1], array('production', 'debug', 'development', 'testing'))) {
        define('APPLICATION_ENV', $nameParts[1]);
    }
}