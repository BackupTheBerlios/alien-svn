<?php

include('alien_cache.php');
include(__ALIEN_LIBSDIR.'output/output.php');
/*
$system->view->loadTemplate('news');
$system->view->loadTemplate('alien_news');
$system->view->loadTemplate('alien_news_item');
*/

/* Alien_Template - это смесь XML, XSLT и PHP */

class alien_view extends alien_cache //implements alien_view 
{
	protected $system = NULL; 
	private $templateSet = 'alien';
	private $pageTemplate = array();
	private $loadedTemplates = array();
	private $templateContents = array();
	private $data = array();
	
	public function __construct($system)
	{
		$this->system = $system;
	}
	
	/*
       This function trys to load a template with the name specified
       (first parameter) for a given plugin (second parameter).
       The returning value is true if the template has been found and added to
       the list of loaded templates and false in the other case.
    */
	
	public function loadTemplate($templateName, $pluginName='')
	{
      if(!$pluginName)
      {
        $pluginName = $this->system->currentPlugin;
      }
      if(!$this->isLoaded($templateName, $pluginName))
      {
        if($this->ifExists($templateName, $pluginName))
        {
            $this->loadedTemplates[$pluginName] = $templateName;
            return true;
        } else {
          error("Template '%s' could not be found", $templateName, true);
          return false;
        }
      } else {
        message("Template '%s' already loaded", $templateName, true);
        return false;
      }
	}
	
	private function getTemplate($pluginName)
    {
        return $this->loadedTemplates[$pluginName];
    }

	private function getStyleSheet($pluginName)
    {

    }
	
	public function renderPlugin($pluginName, $settings, $echo = true)
    {
      $settings = $this->parseSettings($settings);
      $this->system->addSettings($pluginName, $settings);
      $template = $this->getTemplate($pluginName);
      $data = $this->getData($template, $pluginName);
      //print_r($data);
      //$stylesheet = $this->getStylesheet($pluginName);
      $compiledTemplate = $this->compile($template);
      $document = $this;
      ob_start();
      if(array_key_exists(0, $data))
      {
      	foreach($data as $d)
      	{
        	@extract($d);
        	include($compiledTemplate);
      	}
      } else {
      	  	@extract($data);
        	include($compiledTemplate);
      }
      $output = ob_get_contents();
      ob_end_clean();
      if($echo)
       {
           echo $output;
       } else {
           return $output;
       }
    }
	
    /* CSS-like style
       option: value; option2: value2;
    */
	
	private function parseSettings($settings)
    {
       $options_array = explode(';', $settings);
       $outputArray = array();
       foreach($options_array as $value)
       {
           @list($optionName, $optionValue) = explode(':', $value);
           $outputArray[trim($optionName)] = trim($optionValue);
       }
       return $outputArray;
    }
	
     /*
        This function checks if the needed template exists. First it looks in the
        current templateset directory and then in the 'common' directory.
        The function returns file path of the template if it is exists or false
        in the other case.
     */
	
	
	private function ifExists($templateName, $pluginName='')
    {
        $templateSet = $this->templateSet;
        if(file_exists(__ALIEN_TEMPLATESDIR.$templateSet."/".$templateName.".xml"))
		{
			$filepath = __ALIEN_TEMPLATESDIR.$templateSet."/".$templateName.".xml";
		} elseif(file_exists(__ALIEN_TEMPLATESDIR."/common/".$templateName.".xml"))
		{
	        $filepath = __ALIEN_TEMPLATESDIR."/common/".$templateName.".xml";
		}
		return empty($filepath) ? array() : array($templateName, $filepath);
    }

    private function parseTemplate($templateName, $settings)
    {
      $settings = $this->parseSettings($settings);
      if($settings['data'])
      {
        if($data=$this->getData($pluginName, $settings['data']))
        {

        }
      }
    }
	
	public function loadPageTemplate($templateName)
	{
      if($pageTemplate = $this->ifExists($templateName))
      {
		$this->pageTemplate = $pageTemplate;
      } else {
         error("Template '%s' could not be found", $templateName, true);
         return false;
      }
	}
	
	public function getData($templateName, $pluginName)
    {
     return $this->data[$pluginName][$templateName];
    }
	
	public function isLoaded($templateName)
	{
		if(!in_array($templateName, $this->loadedTemplates))
		{
			return false;
		} else return true;
	}
	
	private function isCompiled($templateName, $block='')
	{
		return false;
	}
	
	private function compile($templateName, $block='')
	{
		$paths = $this->getPaths($templateName, $block);
		$templatePath = $paths[0];
		$stylePath = $paths[1];
		if(!empty($templatePath) AND !empty($stylePath))
		{
			if(!$this->isCompiled($templateName))
			{
				$xsl = new DomDocument();
				$xsl->load($stylePath);
				$inputdom = new DomDocument();
				$inputdom->load($templatePath);
				$proc = new XsltProcessor();
				$xsl = $proc->importStylesheet($xsl);
				/* transform and output the xml document */
				$newdom = $proc->transformToDoc($inputdom);
				$data = $newdom->saveHTML();
				$data = str_replace('phpvar:', '<?php echo $', $data);
                $data = str_replace(':phpvar', '; ?>', $data);
				file_put_contents(__ALIEN_TEMPLATESDIR."/compiled/$templateName.php", $data);
                return __ALIEN_TEMPLATESDIR."/compiled/$templateName.php";
			}
		}
	}
	
	private function getPaths($templateName, $block)
	{
    $prefixT = __ALIEN_TEMPLATESDIR.$this->templateSet.'/'.$templateName;
    $prefixC = __ALIEN_TEMPLATESDIR.'common/'.$templateName;
    $prefixT1 = __ALIEN_TEMPLATESDIR.$this->templateSet.'/';
    $prefixC1 = __ALIEN_TEMPLATESDIR.'common/';
    if(strpos($templateName, '\\')===false AND strpos($templateName, '/')===false)
    {
        $templatePath =  $prefixT.'.'.$block.'.'.$this->system->section.'.xml';
    } else {
        $templatePath = $templateName;
    }
	if(!file_exists($templatePath)){
        $templatePath = $prefixC.'.'.$block.'.'.$this->system->section.'.xml';
	    if(!file_exists($templatePath)){
            $templatePath = $prefixT.'.'.$this->system->section.'.xml';
            if(!file_exists($templatePath)){
                $templatePath = $prefixC.'.'.$this->system->section.'.xml';
                if(!file_exists($templatePath)){
                    $templatePath = $prefixT.'.'.$block.'.xml';
					if(!file_exists($templatePath)){
                        $templatePath = $prefixC.'.'.$block.'.xml';
                        if(!file_exists($templatePath)){
                            $templatePath = $prefixT.'.xml';
                            if(!file_exists($templatePath)){
                            $templatePath = $prefixC.'.xml';
                                if(!file_exists($templatePath)){
										$templatePath = '';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    $stylePath = $prefixT.'.'.$block.'.'.$this->system->section.'.xsl';
	if(!file_exists($stylePath)){
        $stylePath = $prefixC.'.'.$block.'.'.$this->system->section.'.xsl';
        if(!file_exists($stylePath)){
          $stylePath = $prefixT.'.'.$this->system->section.'.xsl';
          if(!file_exists($stylePath)){
            $stylePath = $prefixC.'.'.$this->system->section.'.xsl';
            if(!file_exists($stylePath)){
              $stylePath = $prefixT.'.'.$block.'.xsl';
              if(!file_exists($stylePath)){
                $stylePath = $prefixC.'.'.$block.'.xsl';
                if(!file_exists($stylePath)){
                  $stylePath = $prefixT.'.xsl';
                  if(!file_exists($stylePath)){
                    $stylePath = $prefixC.'.xsl';
                    if(!file_exists($stylePath)){
                      $stylePath = $prefixT1.$this->system->section.'.xsl';
                      if(!file_exists($stylePath)){
                        $stylePath = $prefixC1.$this->system->section.'.xsl';
                        if(!file_exists($stylePath)){
                          $stylePath = $prefixT1.'/'.$block.'.xsl';
                          if(!file_exists($stylePath)){
                            $stylePath = $prefixC1.'/'.$block.'.xsl';
                            if(!file_exists($stylePath)){
                              $stylePath = $prefixT1.'/default.xsl';
                              if(!file_exists($stylePath)){
                                $stylePath = '';
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
    }
    
	return array($templatePath, $stylePath);
	}
	
	public function addData($data, $templateName, $pluginName = '')
	{
        if(empty($pluginName))
        {
          $pluginName = $this->system->currentPlugin;
        }
        if(@$this->data[$pluginName][$templateName])
        {
          message("There are already data loaded for the '%s'", $templateName, true);
          return;
          $this->data[$pluginName][$templateName] = array_merge($this->data[$pluginName][$templateName],$data);
        }
		$this->data[$pluginName][$templateName] = $data;
		return true;
	}
	
	public function render($return = false)
	{
        if($this->pageTemplate)
        {
          $file = $this->compile($this->pageTemplate[0]);
          $document = $this;
          //$test = date('r');
          $data = $this->getData($this->pageTemplate[0], $this->pageTemplate[0]);
          extract($data);
          include($file);
        } else {
          error('There is no page template loaded', true);
        }
	}
	
}


?>
