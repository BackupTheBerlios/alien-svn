<?
class gzip
{
	
	private $tarHandler;
	private $archHandler;
	
	public function __construct($archHandler)
	{
		$this->archHandler = $archHandler;
		if(!function_exists('gzopen'))
		{
			die("Your PHP distribution doesn't support this type of archive");
		}
		$this->tarHandler = $this->archHandler->getTarHandler();
		 
	}
	
	public function extract($archive="", $outputDir = "")
	{
		$gzh = gzopen($archive, 'r') or die("Could not open $archive");
		if(!is_dir($outputDir))
		{
			mkdir($outputDir, 0700, true) or die("Could not create a $outputDir directory");
		}
		$_archive = str_ireplace('.tgz', '.gz', $archive);
		$tar_path = basename($_archive, '.gz');
		$lchar = substr($outputDir, -1);
		if('/' == $lchar OR '\\' == $lchar)
		{
			$tar_path = $outputDir.$tar_path;
		} else {
			$tar_path = $outputDir."/".$tar_path;
		}
		$fh = fopen($tar_path, "w");
		while(!feof($gzh))
		{
			$cstr = gzread($gzh, 4096);
			fwrite($fh, $cstr);
		}
		bzclose($gzh);
		fclose($fh);
		$this->tarHandler->extract($tar_path, $outputDir);
		echo "<font color=\"green\">Sucessfull!</font>";
	}
	
	public function compress($fileName)
	{
		$this->tarHandler->compress($fileName);
		$gzh = gzopen($fileName.'.tar.gz', 'wb');
		$th = fopen($fileName.".tar", 'rb');
		while(!feof($th))
		{
			$ustr = fread($th, 1048576);
			gzwrite($gzh, $ustr);
		}
		gzclose($gzh);
		fclose($th);
	}
	
}

?>