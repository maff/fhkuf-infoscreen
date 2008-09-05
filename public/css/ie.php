<?php
require 'lib/stylesheetparser.class.inc.php';
$style = new StylesheetParser();
$style->addStylesheetDir('files_ie', true);
//$style->compressAll = true;
//$style->compressPatterns = array('/^res\/00/');
$style->showSections = true;
$style->Render();
?>