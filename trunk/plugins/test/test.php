<?PHP
set_time_limit(0);
class test
{
	function __construct(&$system)
	{
		for($i=0;$i<100000;$i++)
		{
			/*$system->db->query('SELECT file_name FROM article');
			$result = $system->db->fetchAll();
			$file_name = $result[0]['file_name'];*/
			$file_name = 'index.php';
			$content=file($file_name);
		}
	}
}	
	
?>