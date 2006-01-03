<?php

include('constants.php');
include(__ALIEN_INTERFACESDIR . "/alien_db.php");

class alien_pgsql implements alien_db
{
	private $system = NULL;
	private $link = NULL;
	private $version = NULL;
	private $lastError = NULL;
	private $lastErrorNumber = NULL;
	private $fetchMode = __ASSOCARRAY;
	private $lastQuery = NULL;
	private $queryNum = 0;
	private $connectNum = 0;
	private $tablePrefix = __ALIEN_TABLEPREFIX;
	private $haltOnError = TRUE;
	private $fetchModes = array(__ARRAY, __ASSOCARRAY, __FIELDDIRECT, __FIELD, __FIELDS, __LENGTHS, __OBJECT, __ROW);
	private $usePersistentConnections = false;
	
	public function __construct(&$system)
	{
			$this->system = &$system;
			
			if(!$this->connect())
			{
				$system->MessageCenter->FatalError(__ERROR_NODBCONN, $this->lastError);
			} else {
				$this->version = pg_version($this->link);
				$this->version = $this->version['client'];
			}
	}
	
	public function ifConnected()
	{
		$stat = pg_connection_status($this->link);
		if ($stat === 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function connect($hostname = __ALIEN_DBHOST, $port = __ALIEN_DBPORT, $username = __ALIEN_DBUSER, $password = __ALIEN_DBPASSWORD, $dbname = __ALIEN_DBNAME, $forceNew = 0)
	{
		$conn_string = '';
		if($hostname)
		{
			$conn_string .= "host=".$hostname;
		}
		if($port)
		{
			$conn_string .= " port=".$port;
		}
		if($dbname)
		{
			$conn_string .= " dbname=".$dbname;
		}
		if($username)
		{
			$conn_string .= " user=".$username;
		}
		if($password)
		{
			$conn_string .= " password=".$password;
		}
		if(!$forceNew)
		{
			$link = pg_connect($conn_string);
		} else {
			$link = pg_connect($conn_string, PGSQL_CONNECT_FORCE_NEW);
		}
		if(!$link)
		{
			$this->error();
			return FALSE;
		} else {
			$this->link=$link;
			$this->connectNum++;
			return TRUE;
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
	
	private function error()
	{
		$this->lastError = pg_last_error($this->link);
		//$this->lastErrorNumber = 0;
		if($this->haltOnError)
		{
			$this->system->messageCenter->fatalError(E_DBERROR, $this->lastError);
		}
	}
	
	public function getLastError()
	{
		return $this->lastError;
	}
	
	
	public function getLastErrorNumber()
	{
		//return $this->lastErrorNumber;
		return 0;
	}
	
		
	public function disconnect()
	{
		pg_close($this->link);
		$this->link = NULL;
	}
	/*
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
	*/
	public function getAffectedRows()
	{
		return pg_affected_rows($this->link);
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
		
		if(TRUE==$this->usemysqli)
		{
			return mysqli_change_user ($this->link, $user, $password, $database);
		} else return FALSE;*/
		return true;
	}
	public function getModuleInfo()
	{
		$moduleInfo = array(
	'version' => '0.2.2a',
	'developmentStatus' => 'in-development',
	'site' => 'http://www.never-invited.com/alien/modules/alien_pgsql/',
	'update' => 'http://update.never-invited.com:2331/alien_pgsql',
	'author' => 'Alien Software Corp.',
	'license' => 'Alien Public license',
	'description' => 'PostgreSQL access wrapper for Alien project');
	
	return $moduleInfo;
	}
	
	public function query($queryString)
	{
		$this->lastQuery=pg_query($this->link, $queryString);
		$this->queryNum++;
		if(!$this->lastQuery)
		{
			$this->error();
		}
		return $this->lastQuery;
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
		return pg_num_rows($this->lastQuery);
	}
	
	public function getNumFields()
	{
		return pg_num_fields($this->lastQuery);
	}
	
	public function fetchAll()
	{
		$result = pg_fetch_all($this->lastQuery);
		if($result!=NULL)
		{
			return $result;
		} else return array();
	}
	
	public function fetchOne()
	{
		switch($this->fetchMode)
		{
			case __ARRAY:
			$result=pg_fetch_array($this->lastQuery);
			break;
			case __ASSOCARRAY:
			$result=pg_fetch_assoc($this->lastQuery);
			break;
//			case __FIELDDIRECT:
//			$result=NULL;
//			break;
//			case __FIELD:
//			$result=NULL;
//			break;
//			case __FIELDS:
//			$result=NULL;
//			break;
//			case __LENGTHS:
//			$result=NULL;
//			break;
			case __OBJECT:
			$result=pg_fetch_object($this->lastQuery);
			break;
			case __ROW:
			$result=pg_fetch_row($this->lastQuery);
			break;
			default:
			$result=null;
			break;
		}
		return $result;
	}
	
	public function getProtocolInfo()
	{
		return pg_ping($this->link);
	}
	
	public function ping()
	{
		return pg_version ($this->link);
	}
	
	public function free()
	{
		return pg_free_result($this->lastQuery);
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
		//$this->disconnect();
	}
	
}

?>