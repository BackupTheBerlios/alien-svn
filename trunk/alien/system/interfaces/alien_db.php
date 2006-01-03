<?php

//Немного обновлен.

// {{{ interface alien_db

/**
 *
 * Alien Database Wrapper Interface.
 *
 * @package  DB
 * @author   Oleg Shimchik <oleg.shimchik@gmail.com>
 * @since    PHP 5.0
 * @version  v 0.2.1 alpha
 * @category Database
 */

interface alien_db
{
	
	public function ifConnected();
	
    // {{{ connect()
     
	/**
	 *	Perform a connection to the database using specified login and
	 *  password
	 *	
	 *  @param  string  $host     Database host address (not required)
	 *  @param  string  $user     User's login (not required)
	 *  @param  string  $password User's password (not required)
	 *  @param  string  $name     Database name (not required)
	 *	@return mixed             TRUE on success, or FALSE on failure
	 *	@access public
	 *	
	 */
	 
	public function connect($hostname = __ALIEN_DBHOST, $username = __ALIEN_DBUSER, $password = __ALIEN_DBPASSWORD, $dbname = __ALIEN_DBNAME, $forceNew = 0);
	
	// }}}
    // {{{ disconnect()
	
	/**
	 *  Destroy current database connection
	 *
	 *	@return boolean            true if disconnected or false if not
	 *	@access public
	 *	
	 */
	  
	public function disconnect();
	
	// }}}
	// {{{ changeUser()
	
	/**
	 *  Change logged in user of the active connection
	 *  @param 	string   $user      New user's login
	 *  @param 	string   $password  New user's password
	 *  @param 	string   $database  New database name (if necessary)
	 *  @param 	resource $linkID	A database link identifier 
	 *  						    (if it is a static call)
	 *  @access public 
	 */
	
	public function changeUser($user,$password,$database = NULL);
	
	// }}}
    // {{{ doQuery()

	/**
	 *	Performs a query to the database
	 *
	 *  @param  string  $query	  Query string
	 *  @param  integer $linkID	  A database link identifier 
	 *  						  (if it is a static call)
	 *  @return integer 		  null if the query is successfull
	 * 							  or error identifier if not
	 *  @access public
	 * 
	 */
	 
	public function query($query);
	
	// }}}
    // {{{ seek()

	/**
	 *	Move internal result pointer
	 *
	 *  @return int -1 if the query is successfull or error id if not
	 *  @access public
	 *
	 */

	//!!! public function seek($position);
	
	// }}}
    // {{{ free()
	
	/**
	 *  Free the memory associated with a result
	 *
	 *  @return boolean TRUE on success or FALSE on failure
	 *  @access public
	 *
	 */
	
	// public function free($result);
	
	// }}}
    // {{{ getLinkId()
	
	/**
	 *	Get the link id associated with current connection
	 *  
	 *  @return resource
	 *  @access public
	 *
	 */
	
	//!!! public function getLinkId();
	
	// }}}
    // {{{ getQueryId()
	
	/**
	 *	Get the query id
	 * 
	 *  @return resource
	 *  @access public
	 *
	 */
	
	//!!! public function getQueryId();
	
	// }}}
    // {{{ setDebug()
	
	/**
	 *  Set whether to display debug info or not
	 *
	 *  @return boolean TRUE on success or FALSE on failure
	 *  @access public
	 *
	 */
	
	//!!! public function setDebug($flag);
	
	// }}}
    // {{{ ifDebug()
	
	/**
	 *	Return whether the debug flag is set on or off
	 * 
	 *  @return boolean
	 *  @access public
	 *
	 */
	
	//!!! public function ifDebug();
	
	// }}}
    // {{{ getLastError()
	
	/**
	 *	Get the last error message
	 *
	 *  @return string error message or null if no error occurred
	 *  @access public
	 *
	 */
	
	public function getLastError();
	
	// }}}
    // {{{ getLastErrNo()
	
	/**
	 *  Get the number of the last error
	 *
	 *  @return integer number of the last error or null if no error occurred
	 *  @access public
	 *
	 */
	 
	public function getLastErrorNumber();
	
	// }}}
    // {{{ getNextRecord()
	
	/**
	 *	Return the next record in the query result
	 *
	 *  @see alien_db::select(), alien_db::doRawQuery(), alien_db::setFetchMode()
	 *  @return mixed
	 *  @access public
	 */
	
	//!!! public function getNextRecord();
	
	// }}}
    // {{{ lock()
	
	/**
	 *  Lock the specified table
	 *
	 *  @return boolean TRUE on success or FALSE on failure
	 *  @access public
	 *
	 */
	
	//!!! public function lock($table,$mode);
	
	// }}}
    // {{{ unlock()
	
	/**
	 *	Unlock all tables
	 *  
     *  @see alien_db::lock()
	 *  @return boolean TRUE on success or FALSE on failure
	 *  @access public
	 *
	 */
	
	//!!! public function unlock();
	
	// }}}
    // {{{ getAffectedRows()
	
	/**
	 *	Get number of affected rows in previous database operation
	 *
	 *  @return integer
	 *  @access public
	 */
	
	public function getAffectedRows();
	
	// }}}
    // {{{ getNumRows()
	
	/**
	 *	Get number of rows in result
	 *
	 *  @see 	alien_db::getAffectedRows()
	 *  @param 	integer 	$queryID Query identifier (if it is a static call)
	 *  @return integer
	 *  @access public
	 */
	
	public function getNumRows();
	
	// }}}
    // {{{ getNumFields()
	
    /**
     * Get number of fields in result
     * 
     * @param 	resource 	$queryID Query identifier (if it is a static call)
     * @return 	integer
     * @access 	public
     */
     
	public function getNumFields();
	
	// }}}
    // {{{ getDBInfo()
    
    /**
 	 *  Get information about database
 	 *
 	 *  @param 	integer 	$queryID Query identifier (if it is a static call)
 	 *  @return array
 	 *  @access public   	
     */
	
	//!!! public function getDBInfo();
	
	// }}}
    // {{{ getModuleInfo()
    
    /**
     *  Get all information about current module  
     *
     *  @return array $return_data = array (
     *  'name' => 'My Super MySQL Access wrapper',
     *  'version' => '1.1.2 beta 3',
     *  'developer' => 'Pupkin Ltd',
     *  'product_site' => 'http://pupkinlimited.com/alien_mysql_wraper/',
     *  'update_site' => 'http://update.pupkinlimited.com:8080/alien_mysql_wraper/');
     *  @access public 
     */
	
     
	public function getModuleInfo();
	
	// }}}
    // {{{ EnableChaching()
	
    /**
     *	Enable query chaching
     *
     *  @return boolean TRUE on success or FALSE on failure
	 *  @access public
	 *
     */
    
	//!!! public function EnableCaching();
	
	// }}}
    // {{{ DisableChaching()
    
    /**
     *	Disable query chaching
     *
     *  @return boolean TRUE on success or FALSE on failure
	 *  @access public
	 *
     */
	
	//!!! public function DisableCaching();
	
	// }}}
    // {{{ setFetchMode()
	
    /**
     *  Set fetch mode for the nextRecord()
     *
     *  @see alien_db::select(), alien_db::next_record()
     *  @return boolean TRUE on success or FALSE on failure
	 *  @access public
	 *
     */
     
	public function setFetchMode($mode);
	
}

// }}}

?>