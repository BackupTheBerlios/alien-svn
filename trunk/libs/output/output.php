<?PHP

function error()
{
	$args = func_get_args();
	$debug = array_pop($args);
	$command = 'printf(';
	foreach($args as $num=>$arg)
	{
		if($num != 0)
		{
			$command .= ',"'.addslashes($arg).'"';
		} else {
			$command .= '"'.$arg.'"';
		}
	}
	$command .= ');';
	echo "<font color=\"red\">";
	eval($command);
	echo "</font>";
}
function message()
{
	$args = func_get_args();
	$debug = array_pop($args);
	$command = 'printf(';
	foreach($args as $num=>$arg)
	{
		if($num != 0)
		{
			$command .= ',"'.addslashes($arg).'"';
		} else {
			$command .= '"'.$arg.'"';
		}
	}
	$command .= ');';
	echo "<font color=\"green\">";
	eval($command);
	echo "</font>";
}

?>