<?PHP

class bzip2
{
	
	private $tarHandler;
	private $archHandler;
	
	public function __construct($archHandler)
	{
		$this->archHandler = $archHandler;
		if(!extension_loaded('bz2'))
		{
			//Trying to load a php_bz2 extension.
			//If your hosting provider has turned a safe_mode on or has disabled
			//a dl() function we'll return an error to the user and the archive 
			//won't be uncompressed... It is sad, but it is true.
			 
			if( (bool)ini_get( "enable_dl" ) AND !(bool)ini_get( "safe_mode" ) )
			{
				$prefix = (PHP_SHLIB_SUFFIX == 'dll') ? 'php_' : '';
   				dl($prefix . 'bz2.' . PHP_SHLIB_SUFFIX);
			}
		}
		$this->tarHandler = $this->archHandler->getTarHandler();
		 
	}
	
	public function extract($archive="", $outputDir = "")
	{
		$bzh = bzopen($archive, 'r') or die("Could not open $archive");
		if(!is_dir($outputDir))
		{
			mkdir($outputDir, 0700, true) or die("Could not create a $outputDir directory");
		}
		$_archive = str_ireplace('.tbz', '.bz2', $archive);
		$tar_path = basename($_archive, '.bz2');
		$lchar = substr($outputDir, -1);
		if('/' == $lchar OR '\\' == $lchar)
		{
			$tar_path = $outputDir.$tar_path;
		} else {
			$tar_path = $outputDir."/".$tar_path;
		}
		$fh = fopen($tar_path, "w");
		while(!feof($bzh))
		{
			$cstr = bzread($bzh, 4096);
			fwrite($fh, $cstr);
		}
		bzclose($bzh);
		fclose($fh);
		$this->tarHandler->extract($tar_path, $outputDir);
		echo "<font color=\"green\">Sucessfull!</font>";
	}
	
	public function compress($fileName)
	{
		$this->tarHandler->compress($fileName);
		$bzh = bzopen($fileName.'.tar.bz2', 'wb');
		$th = fopen($fileName.".tar", 'rb');
		while(!feof($th))
		{
			$ustr = fread($th, 1048576);
			bzwrite($bzh, $ustr);
		}
		bzclose($bzh);
		fclose($th);
	}
	
}

?>