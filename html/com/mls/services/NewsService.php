<?php

class NewsService {
	
	public static function getLatest($cnt) {
		$result = array();
		$dao = DBClientHandler::getInstance();
		$query = "SELECT * FROM news ORDER BY date DESC LIMIT 0, $cnt";
		$res = $dao->exec($query);
		$n = array();
		while ( $row = mysql_fetch_array ( $res ) ) {
			$n['id'] = $row['id'];
			$n['caption'] = $row['caption']; 
			$n['text'] = $row['text'];
			$n['headline'] = self::makeHeadline($row['text']);	
			$n['date'] = $row['date'];
			$n['referrer'] = $row['referrer'];
			array_push($result, $n);
		}
		return $result;
	}
	
	public static function makeHeadline($text){
		if (strlen($text) >= 255) {
			$i = 255;
			while ($text[$i] != ' ') {
				$i--;
			}
			if ($i > 0) {
				$text = substr($text, 0, $i) . "...";
			}
		}
		return $text;
	}
}

?>