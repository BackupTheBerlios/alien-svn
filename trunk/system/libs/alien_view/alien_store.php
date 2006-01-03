<?php

$arr = array("A \'bbb", array('a','b','c'));

store::storeArray($arr, 'arr');

class store
{
	static function storeArray(&$array, $arrayName, $fileName = '')
	{
		if(empty($fileName))
			$fileName = 'store_'.$arrayName.'.php';
   		$contents = "$$arrayName = ".stripslashes(var_export($array, true));
		if(file_put_contents($fileName, $contents))
		{
			return true;
		} else return false;
	} 
}