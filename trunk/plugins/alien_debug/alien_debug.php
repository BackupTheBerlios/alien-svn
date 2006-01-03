<?PHP

class alien_debug
{
	function __construct($system)
	{
      ob_start();
	  phpinfo();
	  $data = ob_get_contents();
      ob_end_clean();
      $data = str_replace("<style type=\"text/css\">", "", $data);
      $data = str_replace("</style>", "", $data);
	  $system->document->addData(array('data'=>$data), 'debug', 'debug');
	}
}

?>
