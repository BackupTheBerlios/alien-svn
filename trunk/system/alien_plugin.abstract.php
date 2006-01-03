<?php

abstract class alien_plugin
{
	private $system;
	
	private function __get($varname)
	{
		if(!isset($this->$varname))
		{
			//return //
		}
	}
	
	public function __construct($system)
	{
		$this->system = $system;
		$this->start();
	}
}

?>