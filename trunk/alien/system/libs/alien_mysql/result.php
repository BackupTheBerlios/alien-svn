<?php

class mysql_result
{
	private $usemysqli = TRUE;
	private $result;
	private $fetchMode = __ASSOCARRAY;
	private $fetchFieldDirectFieldNr;
	
	public function __construct($result, $usemysqli = TRUE)
	{
		$this->result = $result;
		$this->usemysqli = $usemysqli;
	}
	
	public function getNumRows()
	{
		if($this->usemysqli)
		{
			return mysqli_num_rows($this->result);
		} else return mysql_num_rows($this->result);
	}
	
	public function getNumFields()
	{
		if($this->usemysqli)
		{
			return mysqli_num_fields($this->result);
		} else return mysql_numfields($this->result);
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
				$result=mysqli_fetch_array($this->result);
				break;
				case __ASSOCARRAY:
				$result=mysqli_fetch_assoc($this->result);
				break;
				case __FIELDDIRECT:
				$result=mysqli_fetch_field_direct($this->result, $this->fetchFieldDirectFieldNr);
				break;
				case __FIELD:
				$result=mysqli_fetch_field($this->result);
				break;
				case __FIELDS:
				$result=mysqli_fetch_fields($this->result);
				break;
				case __LENGTHS:
				$result=mysqli_fetch_lengths($this->result);
				break;
				case __OBJECT:
				$result=mysqli_fetch_object($this->result);
				break;
				case __ROW:
				$result=mysqli_fetch_row($this->result);
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
				$result=mysql_fetch_array($this->result);
				break;
				case __ASSOCARRAY:
				$result=mysql_fetch_assoc($this->result);
				break;
				case __FIELDDIRECT:
				$result=NULL;
				break;
				case __FIELD:
				$result=mysql_fetch_field($this->result);
				break;
				case __FIELDS:
				$result=NULL;
				break;
				case __LENGTHS:
				$result=mysql_fetch_lengths($this->result);
				break;
				case __OBJECT:
				$result=mysql_fetch_object($this->result);
				break;
				case __ROW:
				$result=mysql_fetch_row($this->result);
				break;
				default:
				$result=null;
				break;
			}
			return $result;
		}
	}
	
	public function free()
	{
		if($usemysqli)
		{
			mysqli_free_result($this->result);
		} else mysql_free_result($this->result);
	}
}


?>