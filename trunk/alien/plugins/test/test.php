<?PHP

class test
{
	function __construct($system)
	{

         $result = $system->db->query('SELECT alien_news.news_id, alien_news.news_title, alien_news.news_author, alien_news.news_text, alien_news.news_date, alien_users.user_name FROM alien_news,alien_users WHERE alien_news.news_author = alien_users.user_id ORDER BY alien_news.news_date DESC');
         $data = $result->fetchAll();
         foreach($data as $k=>$v)
         {
           $v['user_link'] = '/user/'.$v['news_author'];
           $v['print_link'] = '/news/print/'.$v['news_id'];
           $v['friend_link'] = '/news/friend/'.$v['news_id'];
           $data[$k] = $v;
         }
         $system->document->loadTemplate('news_item');
         $system->document->addData($data, 'news_item');
	}
	
}

?>
