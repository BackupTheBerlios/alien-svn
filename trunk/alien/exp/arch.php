<?php

include('../libs/archive/archive.php');

$arch = new archive('E:/WebServers/home/alien.com/www/exp/fud.tar.bz2', 'E:/WebServers/home/alien.com/www/exp/fud14');
$arch->compress('fud14');
?>