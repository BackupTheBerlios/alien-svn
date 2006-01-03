<?php

class alien_news
{

	private $system;
	
	public function __construct(&$system)
	{
		$this->system = $system;
		switch(is_numeric($system->request[0]))
		{
			case true:
			switch(@$system->request[1])
			{
				case 'print':
				$system->view->loadPageTemplate('news_print');
				return $this->getNewsById($system->request[0]);
				default:
				$result= $this->getNewsById($system->request[0]);
				break;
			}
			case false:
			switch(@$system->request[1])
			{
				case 'date':
				$date = @$system->request[1];
				return $this->getNewsByDate($date);
				default:
				break;
			}
		}
		if(!isset($system->request[0]) || empty($system->request[0]) || !is_numeric($system->request[0])){
$result = $system->db->query('SELECT alien_news.news_id, alien_news.news_title, alien_news.news_author, alien_news.news_text, alien_news.news_date, alien_users.user_name FROM alien_news,alien_users WHERE alien_news.news_author = alien_users.user_id ORDER BY alien_news.news_date DESC');
	} else $result = $this->getNewsById($system->request[0]);
	//ob_start();
	echo "<pre>";
foreach($result->fetchAll() as $k=>$v)
{
	print_r($v);
}
    echo "</pre>";
	}
	
	private function getNewsById($id)
	{
		return $this->system->db->query('SELECT alien_news.news_id, alien_news.news_title, alien_news.news_text, alien_news.news_date, alien_users.user_name FROM alien_news,alien_users WHERE alien_news.news_author = alien_users.user_id AND alien_news.news_id = '.$id);
	}
	
	private function getNewsByDate()
	{
		
	}
	
	private function getLastNews($number)
	{
		$this->system->db->query('SELECT alien_news.news_id, alien_news.news_title, alien_news.news_text, alien_news.news_date, alien_users.user_name FROM alien_news,alien_users WHERE alien_news.news_author = alien_users.user_id ORDER BY alien_news.news_date DESC LIMIT 0, '.$number);
	}
}

?>
