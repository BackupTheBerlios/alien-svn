<?php

class alien_statistics
{
	var $siteString;

	public function __construct(&$system)
	{
		switch ($system->vars['action'])
		{
			case 'gather':
			$this->addVisitor();
			case '':
		}
	}
	
	public function addVisitor()
	{
		$referer = strtolower($_SERVER['HTTP_REFERER']);
    	$_SERVER['HTTP_USER_AGENT'];
    	$_SERVER['REMOTE_ADDR'];
    	$_SERVER['HTTP_X_FORWARDED_FOR'];
    	$_SERVER['REQUEST_URI'];
	}
	
	public function isUnique()
	{
		if(strstr($referer, $this->siteString))
		return false;
	}
	
	public function isLocal($ip)
	{
		if ($ip == '127.0.0.1' || $ip = '255.255.255.255' ||
		substr($ip, 0, 7) == '192.168' ||
		substr($ip, 0, 3) == '10') return true;
		else return false;
	}
	
}
?>