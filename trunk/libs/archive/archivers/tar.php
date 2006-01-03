<?php


class tar
{
	private $storepaths = true;
	private $prepend = "";
	
	public function __construct()
	{
		
	}
	
	public function compress($fileName, $mask = '*', $isRecursive = true, $exclude = array())
	{
		set_time_limit(0);
		if(!is_array($mask))
		{
			$files = $this->getFiles($fileName, $mask, $isRecursive, $exclude);
			if($mask == '*')
			{
				$files2 = $this->getFiles($fileName, '.*', $isRecursive, $exclude);
				$files = array_merge($files, $files2);
				unset($files2);
			}
		} else {
			$files = array();
			foreach($mask as $current)
			{
				$files2 = $this->getFiles($fileName, $current, $isRecursive, $exclude);
				
			}
		}
		$filen = $fileName.".tar";
		$fh = fopen($filen, 'wb');
		foreach($files as $current)
		{
			/*if(strlen($current['name2']) > 99)
			{
				//$path = substr($current['name2'],0,strpos($current['name2'],"/",strlen($current['name2']) - 100) + 1);
				//$current['name2'] = substr($current['name2'],strlen($path));
				//echo $path."<BR />";
				//echo $current['name2']."<BR />";
				if(strlen($path) > 154 || strlen($current['name2']) > 99)
				{
					echo "Could not add {$path}{$current['name2']} to archive because the filename is too long.";
					continue;
				}
			}*/
			$block = pack("a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12",$current['name2'],decoct($current['stat'][2]),
				sprintf("%6s ",decoct($current['stat'][4])),sprintf("%6s ",decoct($current['stat'][5])),
				sprintf("%11s ",decoct($current['stat'][7])),sprintf("%11s ",decoct($current['stat'][9])),
				"        ",$current['type'],"","ustar","00","Unknown","Unknown","","",!empty($path)? $path : "","");

			$checksum = 0;
			for($i = 0; $i < 512; $i++)
			{
				$checksum += ord(substr($block,$i,1));
			}
			$checksum = pack("a8",sprintf("%6s ",decoct($checksum)));
			$block = substr_replace($block,$checksum,148,8);
			if($current['stat'][7] == 0)
			{
				fwrite($fh,$block);
			}
			else if($fp = @fopen($current['name'],"rb"))
			{
				fwrite($fh, $block);
				while($temp = fread($fp,1048576))
				{
					fwrite($fh,$temp);
				}
				if($current['stat'][7] % 512 > 0)
				{
					$temp = "";
					for($i = 0; $i < 512 - $current['stat'][7] % 512; $i++)
					{
						$temp .= "\0";
					}
					fwrite($fh,$temp);
				}
				fclose($fp);
			}
			else
			{
				echo "Could not open file {$current['name']} for reading. It was not added.";
			}
		}

		fwrite($fh,pack("a512",""));
		fclose($fh);
	}
	
	private function getFiles($fileName, $mask, $isRecursive, $exclude)
	{
		foreach($exclude as $k=>$v)
		{
			//in_array() works in a case-sensitive manner with strings. 
			$exclude[$k] = str_replace('\\', '/', strtolower($v));
		}
		if(is_dir($fileName))
		{
			if($isRecursive)
			{
				$files = $this->globr($fileName, $mask);
			} else {
				$pwd = dirname(__FILE__);
				chdir($fileName);
				$files = glob($mask);
				chdir($pwd);
			}
			foreach($files as $number=>$file)
			{
				if(!in_array($file, $exclude))
				{
					$chars = substr($file, -2);
					if($chars!=".." AND $chars != '\.' AND $chars != "/.")
					{
						if(!is_dir($file))
						{
							$rfiles[$number] = array(
							'name'=>$file,
							'name2'=>$this->prepend.preg_replace("/(\.+\/+)+/","",($this->storepaths == 0 && strstr($file,"/"))? 
							substr($file,strrpos($file,"/") + 1) : $file),
							'type'=>0,
							'ext'=>substr($file,strrpos($file,".")),
							'stat'=>stat($file));
						}
					}
				}
			}
			$files = $rfiles;
			unset($rfiles);
			if(!is_array($files) || empty($files))
			{
				die('No files to add!');
			} else return $files;
		} else {
			if(file_exists($fileName))
			{
				return array($fileName);
			} else die('No files to add!');
		}
	}
	
	private function globr($sDir, $sPattern, $nFlags = NULL)
	{
 		$sDir = escapeshellcmd($sDir);
 		// Get the list of all matching files currently in the
 		// directory.
 		$aFiles = glob("$sDir/$sPattern", $nFlags);
 		// Then get a list of all directories in this directory, and
 		// run ourselves on the resulting array.  This is the
 		// recursion step, which will not execute if there are no
 		// directories.
 		foreach (glob("$sDir/*", GLOB_ONLYDIR) as $sSubDir)
 		{
   			$aSubFiles = $this->globr($sSubDir, $sPattern, $nFlags);
  			$aFiles = array_merge($aFiles, $aSubFiles);
 		}
 		// The array we return contains the files we found, and the
 		// files all of our children found.
 		return $aFiles;
	}
	
	public function extract($archiveName, $outputDir, $overwrite = true)
	{
		chdir($outputDir);
		$fileLName = '';
		$fh = fopen($archiveName, 'r');
		while($block = fread($fh,512))
		{
			$temp = unpack("a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100temp/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp",$block);
			$file = array(
				'name'=>$temp['prefix'] . $temp['name'],
				'stat'=>array(
					2=>$temp['mode'],
					4=>octdec($temp['uid']),
					5=>octdec($temp['gid']),
					7=>octdec($temp['size']),
					9=>octdec($temp['mtime']),
				),
				'checksum'=>octdec($temp['checksum']),
				'type'=>$temp['type'],
				'magic'=>$temp['magic'],
			);
			if($file['checksum'] == 0x00000000)
			{
				break;
			}
			if(trim($file['name']) == '././@LongLink')
			{
				$fileLName = @fread($fh,$file['stat'][7]);
				$fileLName = trim($fileLName);
				@fread($fh,(512 - $file['stat'][7] % 512) == 512? 0 : (512 - $file['stat'][7] % 512));
				$block = fread($fh,512);
				$temp = unpack("a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100temp/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp",$block);
				$temp['name'] = $fileLName;
				$file = array(
				'name'=>$temp['prefix'] . $temp['name'],
				'stat'=>array(
					2=>$temp['mode'],
					4=>octdec($temp['uid']),
					5=>octdec($temp['gid']),
					7=>octdec($temp['size']),
					9=>octdec($temp['mtime']),
				),
				'checksum'=>octdec($temp['checksum']),
				'type'=>$temp['type'],
				'magic'=>$temp['magic'],
				);
			}
			
			else if(trim($file['magic']) != "ustar")
			{
				echo "Unknown archive type!";
			}
			$block = substr_replace($block,"        ",148,8);
			$checksum = 0;
			for($i = 0; $i < 512; $i++)
			{
				$checksum += ord(substr($block,$i,1));
			}
			if($file['checksum'] != $checksum)
			{
				echo "Could not extract from {$archiveName}, it is corrupt.";
			}
			if($file['type'] == 5)
			{
				if(!is_dir($file['name']))
				{
					mkdir($file['name'],$file['stat'][2]);
					chown($file['name'],$file['stat'][4]);
					chgrp($file['name'],$file['stat'][5]);
				}
			}
			else if(!$overwrite && file_exists($file['name']))
			{
				echo "{$file['name']} already exists.";
			}
			else if($new = fopen($file['name'],"wb"))
			{
				fwrite($new,@fread($fh,$file['stat'][7]));
				@fread($fh,(512 - $file['stat'][7] % 512) == 512? 0 : (512 - $file['stat'][7] % 512));
				fclose($new);
				chmod($file['name'],$file['stat'][2]);
				chown($file['name'],$file['stat'][4]);
				chgrp($file['name'],$file['stat'][5]);
			}
			else
			{
				echo "Could not open {$file['name']} for writing.";
			}
		}
		unset($file);
		fclose($fh);
		unlink($archiveName);
	}
	
	
}



?>