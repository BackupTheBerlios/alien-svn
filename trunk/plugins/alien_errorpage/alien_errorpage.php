<?PHP


class alien_errorpage
{
	function __construct($system)
	{
		if(true)
		{
			echo 'You tried to access \''.$_SERVER['REQUEST_URI'].'\', ';
			echo 'but Alien says: <font color=red>403: Access denied.</font> So, go to hell, please.';
			die();
		}
	}
}

?>