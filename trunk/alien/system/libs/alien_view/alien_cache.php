<?PHP

class alien_cache
{
	function __construct()
	{
		//Nothing at this moment.
	}
	
	function isCached($pluginName, $module = '')
	{
		if(empty($module))
		{
			$module = $this->system->section;
		}
		@include(__ALIEN_DATADIR.'cache/'.$module.'/cache.data.php');
		if(isset($cache_data) && $cache_data[$pluginName]['update']<=$cache_data[$pluginName]['cache'])
		{
			return true;
		} else return false;
	}

	function getCache($pluginName, $module = '')
	{
		if(empty($module))
		{
			$module = $this->system->section;
		}
		$content = file_get_contents(__ALIEN_DATADIR.'cache/'.$module.'/'.$pluginName.'.html');
		echo $content;
	}
	
	function updateCache($pluginName, $module='')
	{
		
	}
	
	function writeCache($pluginName, $module = '')
	{
		
	}
}

?>