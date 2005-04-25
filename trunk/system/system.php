<?php
/* CopyRight goes here */

/* Register_globals are total evil. So, let's try to force set them to off. */

ini_set('register_globals', 'off');

class alien_system
{
	var $db = NULL;
	var $document = NULL;
	var $messageCenter = NULL;
	var $data = NULL;
	var $request;
	
	public function __construct()
	{
		
		if(!$this->includeLangFiles()) die();
		$this->messageCenter = $this->getMessageSystem();
		$this->db = $this->getDBInterface();
		$this->document = $this->getCurrentDocument();
		//$this->db->query('error query');
		$this->start();
	}
	
	private function getCurrentDocument()
	{
		return NULL;
	}
	
	private function includeLangFiles()
	{
		if(!@include(__ALIEN_LANGSDIR.__ALIEN_LANGUAGE.'/main.php'))
		{
			return false;
		} else return true;
	}
	
	private function getMessageSystem()
	{
		include(__ALIEN_SYSTEMDIR.'libs/'.__ALIEN_MESSAGECENTER.'/'.__ALIEN_MESSAGECENTER.'.php');
		$className = __ALIEN_MESSAGECENTER;
		return new $className(&$this);
	}
	
	private function getDBInterface()
	{
		include(__ALIEN_SYSTEMDIR.'libs/'.__ALIEN_DBWRAPPER.'/'.__ALIEN_DBWRAPPER.'.php');
		$className = __ALIEN_DBWRAPPER;
		$db = new $className(&$this);
		if($db->ifConnected())
		{
			return $db;
		} else {
			return NULL;
		}
	}
	
	//Start doing something
	
	function start()
	{
		// Заглушка.
		if(substr($_SERVER['REQUEST_URI'], strlen($_SERVER['REQUEST_URI'])-1,  strlen($_SERVER['REQUEST_URI'])) == '/')
		{
			$i = 2;
		} else $i = 1;
		$request = substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI'])-$i);
		$this->request = explode('/', $request);
		$this->data = &$_GET;
		$queue = $this->getQueue();
		foreach($queue as $value)
		{
			$this->data = $this->startPlugin($value);
		} 
	}
	
	function stop()
	{
		$this->document->parse();
	}
	
	function getQueue()
	{
		if(!isset($this->request[0])||empty($this->request[0]))
		{
			$module = 'default';
		} else $module = $this->request[0];
		if(!@include(__ALIEN_DATADIR.'pages/'.$module.'.php'))
		{
			@include(__ALIEN_DATADIR.'pages/default.php');
		} 
		return $queue;
	}
	
	/* Let's start plugining :) */
	
	protected function startPlugin($pluginData)
	{
		/*$data = new $pluginData['name'];
		if(!$data || empty($data) || 0 == array_count_values($data))
		{
			$this->messageCenter->MakeWarning(_NODATARETURNED, $pluginData);
		} else //if($pluginData['type'])
		{
			$this->Data = $data;
		}*/
		
		include(__ALIEN_PLUGINSDIR.$pluginData.'/'.$pluginData.'.php');
		$plugin = new $pluginData(&$this);		
	}
}


?>