<?php

include('constants.php');
include(__ALIEN_INTERFACESDIR . "/alien_db.php");
include("result.php");

class alien_mysql implements alien_db
{
	private $system = NULL;
	private $link = NULL;
	private $version = NULL;
	private $usemysqli = TRUE;
	private $lastError = NULL;
	private $lastErrorNumber = NULL;
	private $fetchMode = __ASSOCARRAY;
	private $lastQuery = NULL;
	private $arrFetchResType = NULL;
	private $fetchFieldDirectFieldNr = NULL;
	private $queryNum = 0;
	private $connectNum = 0;
	private $tablePrefix = __ALIEN_TABLEPREFIX;
	private $haltOnError = TRUE;
	private $fetchModes = array(__ARRAY, __ASSOCARRAY, __FIELDDIRECT, __FIELD, __FIELDS, __LENGTHS, __OBJECT, __ROW);
	private $usePersistentConnections = false;		
	
	public function __construct(&$system)
	{
			$this->system = &$system;
			$this->version = mysql_get_server_info();
			
			/*
				This automatically enables the MySQLi extension (if avaliable).
				Note that some functions avaliable only if the MySQLi extension
				is enabled. For full list of such functions please see
				http://never-invited.com/alien/docs/alien_mysql/mysqli_functions.html
			*/
			
			$this->usemysqli();
			if(!$this->connect())
			{
				$system->MessageCenter->FatalError(__ERROR_NODBCONN, $this->lastError);
			}
	}
	
	public function ifConnected()
	{
		if($this->link)
		{
			return true;
		} else return false;
	}
	
	public function connect($hostname = __ALIEN_DBHOST, $username = __ALIEN_DBUSER, $password = __ALIEN_DBPASSWORD, $dbname = __ALIEN_DBNAME, $forceNew = 0)
	{
		if(!$this->usemysqli)
		{
			$link=mysql_connect($hostname,$username, $password, $forceNew);
			if(!$link)
			{
				$this->error();
				$returnValue=FALSE;
			} else {
				$this->link=$link;
				if($dbname)
				{
					$this->selectDB($dbname);
				}
				$this->connectNum++;
				$returnValue=TRUE;
			}
		} else 
		{
			$link=mysqli_connect($hostname, $username, $password, $dbname);
			if(!$link)
			{
				$this->error();
				$returnValue=FALSE;
			} else 
			{
				$this->link=$link;
				$this->query("SET CHARACTER SET ".__ALIEN_DBCODEPAGE);
				$this->connectNum++;
				$returnValue=TRUE;
			}
		}
		
		return $returnValue;
	}
	
	private function error()
	{
		if($this->usemysqli)
		{
			$this->lastError = mysqli_error($this->link);
			$this->lastErrorNumber = mysqli_errno($this->link);
			if($this->haltOnError)
			{
				$this->system->messageCenter->fatalError(E_DBERROR, '#'.$this->lastErrorNumber.': '.$this->lastError);
			}
		}
	}
	
	public function getLastError()
	{
		return $this->lastError;
	}
	
	public function getLastErrorNumber()
	{
		return $this->lastErrorNumber;
	}
	
		
	public function disconnect()
	{
		if(!$usemysqli)
		{
			mysql_close($this->link);
		} else{
			mysqli_close($this->link);
		}
		$this->link = NULL;
	}
	
	public function selectDB($dbname)
	{
		if(!$this->usemysqli)
		{
			if(mysql_select_db($dbname, $this->link))
			{
				return TRUE;
			} else {
				$this->error();
			}
		} else 
		{
			if(mysqli_select_db($this->link, $dbname))
			{
				return TRUE;
			} else {
				$this->error();
			}
		}
	}
	
	/* 
		Enables the MySQLi extension. Returns current status
		of the usemysqli property. Examples:
		+-----------------------------------------------------------+
		|         Code          | Result |  		Note		    |
	    +-----------------------------------------------------------+
		|$Alien_DB->useMySQLi();| FALSE  | MySQLi is not avaliable  |
		+-----------------------------------------------------------+
		|$Alien_DB->useMySQLi();| TRUE   | Everything is OK			|
		+-----------------------------------------------------------+ 
	*/
	
	public function useMySQLi($usemysqli = TRUE)
	{
		if(TRUE==$usemysqli)
		{
			//MySQLi is only avaliable for MySQL version 4.1.2 and above
			if(version_compare($this->version, '4.1.2')>=0) 
			{
				if(function_exists('mysqli_connect'))
				{
					$this->usemysqli = TRUE;
					return TRUE;
				} else return FALSE;
			} else return FALSE;
		} else
		{
			$this->usemysqli=FALSE;
			return FALSE;
		}
	}
	
	public function getAffectedRows()
	{
		if($this->usemysqli)
		{
			return mysqli_affected_rows($this->link);
		} else {
			return mysql_affected_rows($this->link);
		}
	}
   /*
    *  Adds a prefix to the table name specified.
	*  If the table name is empty this function returns an empty string, not prefix.
	*  To get a prefix, use getPrefix() function.
	*/
	public function addPrefix($table_name)
	{
		if(empty($table_name))
		{
			return "";
		} else {
			return $this->tablePrefix.strval($table_name);
		}
	}
	
	/*
	 * Returns the table name prefix.
	 */
	
	public function getPrefix()
	{
		return strval($this->tablePrefix);
	}
	
	public function getConnectionLink()
	{
		return $this->link;
	}
	
	public function changeUser($user, $password, $database = NULL)
	{
		/*
		   Works only if the MySQLi extension is enabled, because
		   mysql_change_user() is not avaliable in PHP >3.
		*/
		if(TRUE==$this->usemysqli)
		{
			return mysqli_change_user ($this->link, $user, $password, $database);
		} else return FALSE;
	}
	
	public function getModuleInfo()
	{
		$moduleInfo = array(
	'version' => '0.2.2a',
	'developmentStatus' => 'in-development',
	'site' => 'http://www.never-invited.com/alien/modules/alien_mysql/',
	'update' => 'http://update.never-invited.com:2331/alien_mysql',
	'author' => 'Alien Software Corp.',
	'license' => 'Alien Public license',
	'description' => 'MySQL access wrapper for Alien project');
	
	return $moduleInfo;
	}
	
	public function query($queryString)
	{
		if($this->usemysqli)
		{
			$this->lastQuery=mysqli_query($this->link, $queryString);
			$this->queryNum++;
			if(!$this->lastQuery)
			{
				$this->error();
			}
		} else {
			$this->lastQuery=mysql_query($queryString, $this->link);
			$this->queryNum++;
			if(!$this->lastQuery)
			{
				$this->error();
			}
		}
		return new mysql_result($this->lastQuery, $this->usemysqli);
	} 
	
	public function processDump($filePath)
	{
		
	}
	
	public function dumpTables($tables, $includeData)
	{
		
	}
	
	public function setFetchMode($mode)
	{
		if(in_array($mode, $this->fetchModes))
		{
			$this->fetchMode=$mode;
			return true;
		} else return false;
	}
	
	public function getNumRows()
	{
		if($this->usemysqli)
		{
			return mysqli_num_rows($this->lastQuery);
		} else return mysql_num_rows($this->lastQuery);
	}
	
	public function getNumFields()
	{
		if($this->usemysqli)
		{
			return mysqli_num_fields($this->lastQuery);
		} else return mysql_numfields($this->lastQuery);
	}
	
	public function fetchAll()
	{
		if(0!=$this->getNumRows())
		{
			for($i=0;$i<$this->getNumRows();++$i)
			{
				$result[] = $this->fetchOne();
			}
			return $result;
		} else return array();
	}
	
	public function fetchOne()
	{
		if($this->usemysqli)
		{
			switch($this->fetchMode)
			{
				case __ARRAY:
				$result=mysqli_fetch_array($this->lastQuery);
				break;
				case __ASSOCARRAY:
				$result=mysqli_fetch_assoc($this->lastQuery);
				break;
				case __FIELDDIRECT:
				$result=mysqli_fetch_field_direct($this->lastQuery, $this->fetchFieldDirectFieldNr);
				break;
				case __FIELD:
				$result=mysqli_fetch_field($this->lastQuery);
				break;
				case __FIELDS:
				$result=mysqli_fetch_fields($this->lastQuery);
				break;
				case __LENGTHS:
				$result=mysqli_fetch_lengths($this->lastQuery);
				break;
				case __OBJECT:
				$result=mysqli_fetch_object($this->lastQuery);
				break;
				case __ROW:
				$result=mysqli_fetch_row($this->lastQuery);
				break;
				default:
				$result=null;
				break;
			}
			return $result;
		} else
		{	
			switch($this->fetchMode)
			{
				case __ARRAY:
				$result=mysql_fetch_array($this->lastQuery);
				break;
				case __ASSOCARRAY:
				$result=mysql_fetch_assoc($this->lastQuery);
				break;
				case __FIELDDIRECT:
				$result=NULL;
				break;
				case __FIELD:
				$result=mysql_fetch_field($this->lastQuery);
				break;
				case __FIELDS:
				$result=NULL;
				break;
				case __LENGTHS:
				$result=mysql_fetch_lengths($this->lastQuery);
				break;
				case __OBJECT:
				$result=mysql_fetch_object($this->lastQuery);
				break;
				case __ROW:
				$result=mysql_fetch_row($this->lastQuery);
				break;
				default:
				$result=null;
				break;
			}
			return $result;
		}
	}
	
	public function getProtocolInfo()
	{
		if($this->usemysqli)
		{
			return mysqli_get_proto_info($this->link);
		} else return mysql_get_proto_info($this->link);
	}
	
	public function ping()
	{
		if($this->usemysqli)
		{
			return mysqli_ping($this->link);
		} else return mysql_ping($this->link);
	}
	
	public function free()
	{
		if($usemysqli)
		{
			mysqli_free_result($this->lastQuery);
		} else mysql_free_result($this->lastQuery);
	}
	
	public function getStatistics()
	{
		return $array = array(
			$this->queryNum,
			$this->connectNum);
	}
	
	public function setTablePrefix($prefix)
	{
		$this->tablePrefix = $prefix;
	}
	
	public function __destruct()
	{
		//
	}
	
}

?>