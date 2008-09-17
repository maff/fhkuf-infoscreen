<?php
include 'Zend/Debug.php';

$ser = 'a:53:{i:0;s:0:"";i:1;s:3:"...";i:2;s:10:"EEW.vzB.08";i:3;s:8:"EEW05-VZ";i:4;s:8:"EEW06-VZ";i:5;s:8:"EEW07-VZ";i:6;s:5:"EFM07";i:7;s:10:"ERP.bbM.08";i:8;s:4:"FM04";i:9;s:10:"FMI.bbB.08";i:10;s:10:"FMI.vzB.08";i:11;s:8:"FMI05-BB";i:12;s:8:"FMI06-BB";i:13;s:8:"FMI06-VZ";i:14;s:8:"FMI07-BB";i:15;s:8:"FMI07-VZ";i:16;s:10:"IBS.bbB.08";i:17;s:10:"IBS.vzB.08";i:18;s:5:"IBS04";i:19;s:8:"IBS05-BB";i:20;s:8:"IBS05-VZ";i:21;s:8:"IBS06-BB";i:22;s:8:"IBS07-BB";i:23;s:8:"IBS07-VZ";i:24;s:10:"IFC.bbM.08";i:25;s:10:"IMS.bbM.08";i:26;s:4:"IP07";i:27;s:4:"IP08";i:28;s:6:"IWFM04";i:29;s:10:"KSM.bbM.08";i:30;s:8:"KSM06-BB";i:31;s:8:"KSM07-BB";i:32;s:6:"MA2008";i:33;s:5:"NGO07";i:34;s:11:"SKVM.bbB.08";i:35;s:11:"SKVM.bbM.08";i:36;s:11:"SKVM.vzB.08";i:37;s:9:"SKVM05-BB";i:38;s:9:"SKVM05-VZ";i:39;s:9:"SKVM06-BB";i:40;s:9:"SKVM06-VZ";i:41;s:9:"SKVM07-BB";i:42;s:9:"SKVM07-VZ";i:43;s:9:"UF.vzB.08";i:44;s:7:"UF06-VZ";i:45;s:7:"UF07-VZ";i:46;s:5:"V2007";i:47;s:5:"V2008";i:48;s:9:"WI.vzB.08";i:49;s:4:"WI04";i:50;s:7:"WI05-VZ";i:51;s:7:"WI06-VZ";i:52;s:7:"WI07-VZ";}';

$array = unserialize($ser);

$new = array('');
foreach($array as $val)
{
    if(fltr($val)) $new[] = $val;
}

Zend_Debug::dump($array);

sort($new);
Zend_Debug::dump($new);


echo serialize($new);

    function fltr($value)
	{
		$cache = true;
		
		if(empty($value))
			$cache = false;
		
        // don't cache on multiple values
		if(strpos($value, ',') !== false)
			$cache = false;
			    
		if(strpos($value, '/') !== false)
			$cache = false;
		
        // don't cache on NN value (undefined)
		if(strpos($value, 'NN') !== false)
		    $cache = false;
        
        // don't cache without having any letters or numbers
        if(!preg_match('/[a-zA-Z0-9]+/i', $value))
            $cache = false;
	
		return $cache;
	} 