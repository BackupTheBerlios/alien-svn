<?PHP

class quote
{
	public function quote($system)
	{
		$quotes = file('http://alien.com/plugins/quote/cookies.txt');
		$quote = $quotes[ mt_rand(0, count($quotes) ) ];
		$quote = trim($quote);
		$system->document->loadTemplate('quote');
		$system->document->addData(array('quote'=>$quote), 'quote', 'quote');
	}
}

?>