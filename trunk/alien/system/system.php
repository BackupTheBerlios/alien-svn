<?php
/* CopyRight goes here */

ini_set('register_globals', 'off');

class alien_system
{
	public $db = NULL;
	public $document = NULL;
	public $messageCenter = NULL;
	public $data = NULL;
	public $user = NULL;
	public $request = array();
	public $section = 'default';
	public $currentPlugin = '';
	private $missingPlugins = array();
	private $loadedLibs = array();
	private $stop = false;
	
	public function __construct()
	{
		if(!$this->includeLangFiles()) die();
		$options = $this->getOptions();
		$this->request = $options;
		$this->getRequest();
		$this->extractSection();
		$this->user = $this->attachLib(__ALIEN_USER, array('$this'), true);
		$this->document = $this->attachLib(__ALIEN_DOCUMENT, array('$this'), true);
  $this->messageCenter = $this->attachLib(__ALIEN_MESSAGECENTER, array('$this'), true);
		$this->db = $this->attachLib(__ALIEN_DBWRAPPER, array('$this'), true);
		$this->start();
	}
	
	public function attachLib($libName, $constructParams = array(), $system=false, $forceNew = false)
	{
		if(($forceNew) OR (!$forceNew AND !key_exists($libName, $this->loadedLibs)))
		{
			$path = '';
			$i = 0;
			$paramString = '';
			if($system)
			{
				$path .= __ALIEN_SYSTEMLIBSDIR;
			} else {
				$path .= __ALIEN_LIBSDIR;
			}
			$path .= $libName.'/'.$libName.'.php';
			foreach($constructParams as $key => $value)
			{
				if(0!=$i)
				{
					$paramString .= ' , '.$value;
				} else {
					$paramString .= $value;
				}
				++$i;
			}
			if(!include($path))
			{
				return NULL;
			} else {
				eval('$obj = new '.'$libName('.$paramString.');');
				$this->loadedLibs[$libName] = $obj;
				return $obj;
			}
		} elseif(!$forceNew AND key_exists($libName, $this->loadedLibs))
		{
			return $this->loadedLibs[$libName];
		}
	}
	
	private function includeLangFiles()
	{
		if(!@include(__ALIEN_LANGSDIR.'/'.__ALIEN_LANGUAGE.'/main.php'))
		{
			return false;
		} else return true;
	}
	
	//Start doing something
	
	function start()
	{
		$queue = $this->getQueue();
		$this->document->loadPageTemplate($this->section);
		foreach($queue as $k=>$value)
		{
			if(!$this->document->isCached($value))
			{
				$this->data = $this->startPlugin($value);
			} else $this->document->getCache($value);
		} 
	}
	
	function getRequest()
	{
		// Заглушка.
		if(array_key_exists('REDIRECT_URL', $_SERVER)){
			$request = $_SERVER['REDIRECT_URL'];
		} else {
			$request = $_GET['q'];
		}
		if(substr($request, -1, 1) == '/')
		{
			$i = 2;
		} else $i = 1;
		$request = substr($request, 1, strlen($request)-$i);
		$this->request = array_merge($this->request, explode('/', $request));
	}
	
	function getDB($forceNew = false)
	{
		
	}
	
	function stop()
	{
		$this->stop = true;
		$this->document->render();
	}
	
	public function addSettings($pluginName, $settings)
    {

    }
	
	function extractSection()
	{
		if(isset($this->request[0])||!empty($this->request[0]))
		{
			$this->section = $this->request[0];
		}
	}
	
	function addPlugin2Page($type, $functionName, $fileName, $className)
	{
		
	}
	
	function getQueue()
	{
		if(!@include(__ALIEN_DATADIR.'pages/'.$this->section.'.php'))
		{
            $this->section = 'default';
			@include(__ALIEN_DATADIR.'pages/default.php');
			if(!isset($queue) || empty($queue))
			{
				die('System is down');
			}
		} else {
            array_shift($this->request);
			if(!isset($queue) || empty($queue))
			{
				die('System is down');
			}
		}
		return $queue;
	}
	
	/* Let's start plugining :) */
	
	function getOptions()
	{
		if(!@include(__ALIEN_DATADIR.'pages/'.$this->section.'.php')||!isset($options))
		{
			$options = array();
		} 
		
		return $options;
	}
	
	public function changeDBWrapper($newDBWrapper, $path = "")
	{
		//...
	}
	
	public function changeTemplateSystem($newTemplateSystem, $path= "")
	{
		//...
	}
	
	protected function startPlugin($pluginData)
	{
		if(!include(__ALIEN_PLUGINSDIR.$pluginData.'/'.$pluginData.'.php'))
		{
			// Trying to get cache for it
			// Generating Error
			$this->missingPlugins[] = $pluginData;
		} else {
            $this->currentPlugin = $pluginData;
            $plugin = new $pluginData(&$this);
        }
	}
	
	public function __destruct()
	{
       $this->stop();
    }
}


?>
