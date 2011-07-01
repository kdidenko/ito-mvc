<?php

class ListingService {
	
	public static function getLatest($cnt) {
		$result = array();
		$dao = DBClientHandler::getInstance();
		$query = "SELECT l.post_id AS id, l.r_what, l.r_region, l.r_city, l.r_price, l.r_street, 
				a.search_caption, a.filter_caption, a.Action_caption, t.what_caption, s.name 
				FROM realty_data l LEFT JOIN actions a ON l.r_type = a.a_id LEFT JOIN types t ON l.r_what = t.t_id 
				LEFT JOIN settlements s ON l.r_region = s.s_id 
				ORDER BY id DESC LIMIT 0, {$cnt}";
		$res = $dao->exec($query);
		$l = array();
		while ( $row = mysql_fetch_array ( $res ) ) {
			$l['id'] = $row['id'];
			$l['property_type'] = $row['r_what'];
			$l['region'] = $row['r_region'];
			$l['city'] = $row['r_city'];
			$l['price'] = $row['r_price'];
			$l['address'] = $row['r_street'];
			//TODO: get reed of next params (useless!) 
			$l['search_caption'] = $row['search_caption'];
			$l['filter_caption'] = $row['filter_caption'];
			$l['action_caption'] = $row['Action_caption'];
			$l['what_caption'] = $row['what_caption'];
			$l['name'] = $row['name'];
			array_push($result, $l);
		}
		return $result;
	}

}

?>