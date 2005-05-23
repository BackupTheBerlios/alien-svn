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
	var $request = array();
	
	
	public function __construct()
	{
		
		if(!$this->includeLangFiles()) die();
		$this->getUser();
		$this->document = $this->getDocument();
		$this->messageCenter = $this->getMessageSystem();
		$this->db = $this->getDBInterface();
		$this->start();
	}
	
	private function getUser()
	{
		include(__ALIEN_SYSTEMDIR.'libs/'.__ALIEN_USER.'/'.__ALIEN_USER.'.php');
		$className = __ALIEN_USER;
		return new $className(&$this);
	}
	
	private function getDocument()
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
		$options = $this->getOptions();
		$this->request = $options;
		$this->getRequest();
		$queue = $this->getQueue();
		foreach($queue as $k=>$value)
		{
			if(!$this->isCached($value))
			{
				$this->data = $this->startPlugin($value);
			} else $this->getCache($value);
		} 
	}
	
	function isCached($plugin_name)
	{
		if(!isset($this->request[0])||empty($this->request[0]))
		{
			$module = 'default';
		} else $module = $this->request[0];
		@include(__ALIEN_DATADIR.'cache/'.$module.'/cache.data.php');
		if(isset($cache_data) && $cache_data[$plugin_name]['update']<=$cache_data[$plugin_name]['cache'])
		{
			return true;
		} else return false;
	}

	function getCache($plugin_name)
	{
		if(!isset($this->request[0])||empty($this->request[0]))
		{
			$module = 'default';
		} else $module = $this->request[0];
		$content =file_get_contents(__ALIEN_DATADIR.'cache/'.$module.'/'.$plugin_name.'.html');
		echo $content;
	}
	
	function getRequest()
	{
		// Заглушка.
		if(array_key_exists('REDIRECT_URL', $_SERVER)){
			$request = $_SERVER['REDIRECT_URL'];
		} else {
			$request = $_SERVER['REQUEST_URI'];
		}
		if(substr($request, -1, 1) == '/')
		{
			$i = 2;
		} else $i = 1;
		$request = substr($request, 1, strlen($request)-$i);
		$this->request = array_merge($this->request, explode('/', $request));
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
			$queue = array($this->request[0]);
		} 
		return $queue;
	}
	
	/* Let's start plugining :) */
	
	function getOptions()
	{
		if(!isset($this->request[0])||empty($this->request[0]))
		{
			$module = 'default';
		} else $module = $this->request[0];
		if(!@include(__ALIEN_DATADIR.'pages/'.$module.'.php')||!isset($options))
		{
			$options = array();
		} 
		
		return $options;
	}
	
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