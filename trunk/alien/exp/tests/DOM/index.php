<?php
//set_time_limit(0);
$iterations = isset($_GET['i'])? $_GET['i'] : 1000;
$file = isset($_GET['f'])? $_GET['f'] : 'doc.xml';
//include("domit/xml_domit_lite_include.php");
for($t = 0; $t<=2; $t++)
{
	$time_s = microtime(true);
	for($i=0; $i<$iterations; $i++)
	{
		switch($t)
		{
			case 0: 
			$dom = new DomDocument;
			$dom->preserveWhiteSpace = FALSE;
			$xml = file_get_contents($file);
			$dom->loadXML($xml);
			$params = $dom->getElementsByTagName('phpvar');
			break;
			case 1:
			$xml = file_get_contents($file);
			preg_match_all("#<phpvar>(.*)</phpvar>#i", $xml, $matches);
			break;
			//case 2:
			//$dom =& new DOMIT_Lite_Document();
			//$dom->loadXML($file);
			//$params = $dom->getElementsByTagName('phpvar');
			//break;
			case 2: 
			$xml = file_get_contents($file);
			$arr = array();
			$x = explode("<phpvar>",$xml);
			foreach($x as $v) 
			{
				$pos = strpos($v,'</phpvar>');
				$v = substr($v,0,$pos);
				if ($v) $arr[] = $v;	
			} 
		}	
	}
	$time_e = microtime(true);
	switch($t)
	{
		case 0:
		$a = 'dom';
		break;
		case 1:
		$a = 'regexp';
		break;
		//case 2:
		//$a = 'domit';
		//break;
		case 2:
		$a = 'explode';
		break;
	}
	$time[$a] = $time_e - $time_s;
}
asort($time);
echo "<b>Iterations:</b> $iterations<BR />";
echo "<ul>";
$n = 0;
foreach($time as $k=>$v)
{
	$n++;
	echo "<li>$n <b>Type:</b> $k. <b>Time:</b> $v";
}
echo "</ul>";
?>