<?php
/* Copyright goes here... */
//phpinfo();
ob_start();
$time_s = microtime(true);
//$_REQUEST["PHPCOVERAGE_HOME"] = '/home/alien.com/www/src';
//include('src/phpcoverage.remote.top.inc.php');
include("config/main.config.php");
include("config/system.modules.config.php");
include(__ALIEN_SYSTEMDIR."system.php");
$system = new alien_system();
$db_queries = $system->db->getStatistics();
$db_queries = $db_queries[0];
$time_e = microtime(true);
$time = $time_e - $time_s;
echo "<p align=center>Generated in: $time second(s). There were $db_queries queries</p>";
//include('src/phpcoverage.remote.bottom.inc.php');
	$content = ob_get_contents();
		$content = gzencode($content);
		ob_end_clean();
		header('Content-Encoding: gzip');
		echo $content;
?>