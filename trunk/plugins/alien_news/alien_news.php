<?php

class alien_news
{

	var $system;
	
	public function __construct(&$system)
	{
		echo "<h1 align=center>Alien Times</h1>";
		$this->system = &$system;
		if(!isset($system->request[1]) || empty($system->request[1]) || !is_numeric($system->request[1])){
$system->db->query('SELECT alien_news.news_id, alien_news.news_title, alien_news.news_text, alien_news.news_date, alien_users.user_name FROM alien_news,alien_users WHERE alien_news.news_author = alien_users.user_id ORDER BY alien_news.news_date DESC');
	} else $this->getNewsById($system->request[1]);
foreach($system->db->fetchAll() as $k=>$v)
{
	echo "#".$v['news_id']." <b>".$v['news_title']."</b>";
	echo "<p>".$v['news_text']."</p>";
	echo "<i>Author: ".$v['user_name']." Date: ".$v['news_date']."</i><BR /><BR />";
}

	}
	
	private function getNewsById($id)
	{
		$this->system->db->query('SELECT alien_news.news_id, alien_news.news_title, alien_news.news_text, alien_news.news_date, alien_users.user_name FROM alien_news,alien_users WHERE alien_news.news_author = alien_users.user_id AND alien_news.news_id = '.$id);
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