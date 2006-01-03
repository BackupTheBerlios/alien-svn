<?php

include('constants.php');

class archive
{
	private $archiveName;
	
	private $archiveType;
	
	/* The compression level. From 0 (none) to 9 (max) */
	
	private $compressLevel = 9;
	
	private $outputDir;
	
	private $archiverInstance = NULL;	
	
	public function __construct($archiveName = "", $outputDir = "", $archiveType = "", $compressLevel = 9)
	{
		if(!empty($archiveName))
		{
			$this->archiveName = $archiveName;
		}
		if(!empty($outputDir))
		{
			$this->outputDir = $outputDir;
		}
	}
	
	//
	// Types:
	// bz2, zip, tar, gzip
	//
	
	public function setType($type = "bz2")
	{
		$types = array('bz2', 'bzip2', 'zip', 'tar', 'gzip', 'gz');
		if(isset($type) AND !empty($type))
		{
			if(in_array($type, $types))
			{
				$this->archiveType = $type;
				return true;
			} else return false;
		} else return false;
	}
	
	private function checkType($filename)
	{
		if (@file_exists($filename))
		{
			if ($fp = @fopen($filename, "rb"))
			{
				$sign = fread($fp, 2);
				fclose($fp);
				if ($sign == "\37\213")
				{
					return 'gzip';
                                } elseif ($sign == "BZ") 
				{
					return 'bz2';
				} elseif ($sign == "PK")
				{
					return 'zip';
				}
				return 'unknown';
			}
		}
	}
	
	public function addFiles()
	{
		
	}
	
	public function addDir()
	{
		
	}
	
	public function compress($fileName, $overwrite = true)
	{
		if(!$this->archiverInstance)
		{
				include_once('archivers/bzip2.php');
				$this->archiverInstance = new bzip2($this);
		}
		$this->archiverInstance->compress($fileName);
	}
	
	public function getTarHandler()
	{
		static $tarInstance;
		if(!$tarInstance)
		{
			include_once('archivers/tar.php');
			$tarInstance = new tar;
		} 
		return $tarInstance;
	}
	
	public function extract($archive="", $outputDir="")
	{
		if(empty($archive))
		{
			$archive = $this->archiveName;
		}
		if(empty($outputDir))
		{
			$outputDir = $this->outputDir;
		}
		if(!$this->archiveType)
		{
				$ext = substr($archive, -3);
				$this->setType($ext); 
		}
		if(!$this->archiverInstance)
		{
			switch($this->archiveType)
			{
				case 'tbz':
				case 'bz2':
				include_once('archivers/bzip2.php');
				$this->archiverInstance = new bzip2($this);
			}
		}
		$this->archiverInstance->extract($archive, $outputDir);
	}
	
}


?>