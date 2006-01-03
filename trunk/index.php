<?php
/* Alien v 0.1-dev */
//phpinfo();
ob_start();
$time_s = microtime(true);
include("config/main.config.php");
include("config/system.modules.config.php");
include(__ALIEN_SYSTEMDIR."system.php");
$system = new alien_system();
$db_queries = $system->db->getStatistics();
$db_queries = $db_queries[0];
$time_e = microtime(true);
$time = $time_e - $time_s;
$system->document->addData(array('test' => "<p align=center>Generated in: <a href=/debug/>$time</a> second(s). There were <a href=/debug/>$db_queries</a> queries</p>"), $system->section, $system->section);
?>
