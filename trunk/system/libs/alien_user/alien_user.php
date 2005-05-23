<?php
	
class alien_user //implements alien_user
{
	public function  __construct($system)
	{
		session_set_save_handler(array(& $this, 'open'), array(& $this, 'close'), array(& $this, 'read'), array(& $this, 'write'), array(& $this, 'destroy'), array(& $this, 'gc'));
		session_start();
		session_register('aaa', 'bbb');
	}
	
	private function read($id) 
	{
		$sess_file = __ALIEN_SESSPATH."sess_$id";
		if ($fp = @fopen($sess_file, "r")) {
			$sess_data = fread($fp, filesize($sess_file));
			return($sess_data);
		} else {
			return(""); // Must return "" here.
		}
	}
	
	function write($id, $sess_data) 
	{
		$sess_file = __ALIEN_SESSPATH."sess_$id";
		if ($fp = @fopen($sess_file, "w")) {
			return(fwrite($fp, $sess_data));
		} else {
			return(false);
		}

	}
	
	function open($save_path, $session_name) 
	{
		return(true);
	}

	function close() 
	{
		return(true);
	}
	
	function destroy($id) 
	{
		$sess_file = __ALIEN_SESSPATH."sess_$id";
		return(@unlink($sess_file));
	}

	
	private function gc($maxlifetime) 
	{
		return true;
	}
}
?>