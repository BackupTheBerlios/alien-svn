<?php
$iterations = isset($_GET['i'])? $_GET['i'] : 100000;

function in_array_i($needle, $haystack, $strict = FALSE)
{
	if(is_string($needle))
	{
		for($i=0; $i<sizeof($haystack); $i++)
		{
			$value = array_shift($haystack);
			if(strtolower($value)==strtolower($needle))
			{
				return true;
			}
		}
		return false;
	} else {
		return in_array($needle, $haystack, $strict);
	}
}

function in_array_i2($needle, $haystack, $strict = FALSE)
{
	if(is_string($needle))
	{
		foreach($haystack as $value)
		{
			if(strtolower($value)==strtolower($needle))
			{
				return true;
			}
		}
		return false;
	} else {
		return in_array($needle, $haystack, $strict);
	}
}

function in_array_i3($needle, $haystack, $strict = FALSE)
{
	if(is_string($needle))
	{
		$haystack = strtolower(implode(' ', $haystack));
		if(strpos($haystack, strtolower($needle))!==FALSE)
		{
			return true;
		} else return false;
	} else {
		return in_array($needle, $haystack, $strict);
	}
}

function in_array_i4($needle, $haystack, $strict = FALSE)
{
	if(is_string($needle))
	{
		array_walk($haystack, 'arr_strtolower');
	}
	
	return in_array(strtolower($needle), $haystack, $strict);
}

function arr_strtolower(&$value, $key)
{
	$value = strtolower($value);
}

$example = array('AbC', 'DeF', 'G');

for($i=0; $i<4; $i++)
{
	$s_time = microtime(true);
	switch($i)
	{
		case 0:
		for($j=0; $j<$iterations; $j++)
		{
			$res = in_array_i('g', $example);
		}
		break;
		case 1:
		for($j=0; $j<$iterations; $j++)
		{
			$res = in_array_i2('g', $example);
		}
		break;
		case 2:
		for($j=0; $j<$iterations; $j++)
		{
			$res = in_array_i3('g', $example);
		}
		break;
		case 3:
		for($j=0; $j<$iterations; $j++)
		{
			$res = in_array_i4('g', $example);
		}
		break;
	}
	$e_time = microtime(true);
	switch($i)
	{
		case 0:
		$a = 'for() with array_shift()';
		break;
		case 1:
		$a = 'foreach()';
		break;
		case 2:
		$a = 'implode()';
		break;
		case 3:
		$a = 'array_walk()';
		break;
	}
	$results[$a] = $e_time - $s_time;
}
asort($results);
echo "<b>Iterations:</b> $iterations<BR />";
echo '<ul>';
$n = 0;
$old_v = 0;
foreach($results as $k=>$v)
{
	$n++;
	echo "<li>$n <b>Type:</b> $k. <b>Time:</b> $v";
	if($old_v !== 0)
	{
		echo '.<b> It is</b> '.$v/$old_v.'<b> times slower</b> </li>';
	}
	if($n==1)
	{
		$old_v = $v;
	}
}
echo '</ul>';

?>