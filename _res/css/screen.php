<?php
require 'lib/stylesheetparser.class.inc.php';
$style = new StylesheetParser();
$style->addStylesheetDir('files_screen', true);
$style->compressAll = true;
//$style->compressPatterns = array('/^files_screen\/00/');
$style->showSections = true;
$style->Render();
?>
