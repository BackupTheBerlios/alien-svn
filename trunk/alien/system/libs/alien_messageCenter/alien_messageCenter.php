<?php

// IMPLEMENT ALIEN_VIEW!!!


class alien_messageCenter //implements alien_error
{
	public $lastMessage;
	public $errorType = E_ALL;
	/*
	 	The constructor of this class. If you don't understand
	 	what it is, close this file immediately :-)
	*/
	
	public function __construct()
	{
		
	}
	
	
	public function message($message)
	{
		// Status: gag
		echo $message;
	}
	
	public function error($error)
	{
		// Status: gag
		echo $error;
	}
	
	public function fatalError($error, $debugData = '')
	{
		// Status: gag
		echo $error;
		if($debugData)
		echo '<BR>'.M_DEBUGINFO.'<BR>';
		print_r($debugData);
		die();
	}
	
	/* Experimental */
	
	public function addErrorType($type)
	{
		
	}
	
}


?>