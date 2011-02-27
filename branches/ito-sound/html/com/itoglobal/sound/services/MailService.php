<?php

class MailService {
	/**
	 * @var string defining the mails table name
	 */
	const MAILS = 'mails';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the subject field name
	 */
	const SUBJECT = 'subject';
	/**
	 * @var string defining the text field name
	 */
	const TEXT = 'text';
	/**
	 * @var string defining the sender_id field name
	 */
	const SENDER_ID = 'sender_id';
	/**
	 * @var string defining the getter_id field name
	 */
	const GETTER_ID = 'getter_id';
	/**
	 * @var string defining the crdate field name
	 */
	const CRDATE = 'crdate';
	/**
	 * @var string defining the status field name
	 * @param 1 - trash; 2 - drafts
	 */
	const STATUS = 'status';
	/**
	 * @var string defining the opened field name
	 * @param 0 - new ; 1 - read
	 */
	const OPENED = 'opened';
	/**
	 * @var string defining the hash field name
	 */
	const HASH = 'hash';
	
	const NEW_MAILS = 'new_mails';
	const SENDER = 'sender';
	const GETTER = 'getter';
	const SEND = 'send';
	const RE = 're';
	const FWD = 'fwd';
	const INBOX = 'inbox';
	const OUTBOX = 'outbox';
	const TRASH = 'trash';
	const DRAFTS = 'drafts';
	
	
	/**
	 * Retrieves the users mails by specified user id.
	 * 
	 * SQL:
	 * SELECT t1.*, users.username as getter FROM (
	 * SELECT mails.*, users.username as sender
	 * FROM  mails
	 * LEFT JOIN users ON mails.sender_id=users.id
	 * ) as t1
	 * LEFT JOIN users ON t1.getter_id=users.id
	 * 
	 * @param integer $user the user id.
	 * @return mixed mails data or null if user with such id does not exists. 
	 */
	public static function getMails ($where, $limit = NULL){
		$fields = 't1.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::GETTER . SQLClient::FROM . '(' . 
					SQLClient::SELECT . self::MAILS . '.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::SENDER;
		$from = self::MAILS . SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . self::MAILS . '.' . 
				self::SENDER_ID . '=' . UsersService::USERS . '.' . UsersService::ID . ')' . SQLClient::SQL_AS . 't1' . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 't1.' . 
				self::GETTER_ID . '=' . UsersService::USERS . '.' . UsersService::ID;
		$orderby = self::CRDATE . ' ' . SQLClient::DESC;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , $orderby, $limit );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	/**
	 * @param array $array data
	 * @param bool $status get time only for opened
	 * @param bool $gettime get time for all
	 * @return unknown_type
	 */
	public function createDate($array, $status = false, $gettime=false){
		foreach($array as $key => $value){
			$data = $value[MailService::CRDATE];
			$data = explode(' ', $data);
			$time = substr($data[1], 0, -3);
			$data = explode('-',$data[0]);
			$data = $data[2] . '/' . $data[1] . '/' . $data[0];
			if ($status==true){
				$data .= $value[MailService::OPENED]==0 || $gettime==true? ' ' . $time : NULL;
			}
			$array[$key][MailService::CRDATE] = $data;
		}
		return $array;
	}
	
	public static function countNew($user) {
		$fields = SQLClient::COUNT . '(' . self::ID . ')' . SQLClient::SQL_AS . self::NEW_MAILS;
		$where = self::GETTER_ID . '=' . $user;
		$where .= ' AND ' . self::OPENED . '=0';
		$where .= ' AND ' . self::STATUS . '=3';
		$from = self::MAILS;
		$group = self::STATUS;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $group , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : false;
		return $result;
	}
	
	public static function getInbox($user, $limit=NULL ) {
		$where = self::GETTER_ID . '=' . $user;
		$where .= ' AND ' . self::STATUS . '=3';
		$result = self::getMails($where, $limit);
		$result = $result != false ? self::createDate($result, true) : false;
		return $result;
	}
	
	public static function getOutbox($user, $limit=NULL ) {
		$where = self::SENDER_ID . '=' . $user;
		$where .= ' AND ' . self::STATUS . '=4';
		$result = self::getMails($where, $limit);
		$result = $result != false ? self::createDate($result) : false;
		return $result;
	}
	
	public static function getDrafts($user, $limit=NULL ) {
		$where = self::GETTER_ID . '=' . $user;
		$where .= ' AND ' . self::STATUS . '=2';
		$where .= ' OR ' . self::SENDER_ID . '=' . $user;
		$where .= ' AND ' . self::STATUS . '=2';
		$result = self::getMails($where, $limit);
		return $result;
	}
	
	public static function getTrash($user, $limit=NULL ) {
		$where = self::GETTER_ID . '=' . $user;
		$where .= ' AND ' . self::STATUS . '=1';
		$where .= ' OR ' . self::SENDER_ID . '=' . $user;
		$where .= ' AND ' . self::STATUS . '=1';
		$result = self::getMails($where, $limit);
		$result = $result != false ? self::createDate($result, true) : false;
		return $result;
	}
	
	public static function readMail($hash) {
		$fields = array('0' => self::OPENED);
		$vals = array('0' => '1');
		self::updateMail($hash, $fields, $vals);
	}
	
	public static function goTrash($hash) {
		$fields = array('0' => self::STATUS);
		$vals = array('0' => '1');
		self::updateMail($hash, $fields, $vals);
	}
	
	public static function goDrafts($id) {
		$fields = array('0' => self::STATUS);
		$vals = array('0' => '2');
		self::updateMail($id, $fields, $vals);
	}
	
	private static function updateMail ($hash, $fields, $vals){
		$from = self::MAILS;
		$where = self::HASH . " = '" . $hash . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	/**
	 * Retrieves mail by specified mail id.
	 * @param integer $hash the mail id
	 * @return mail data
	 */
	public static function getMail($hash) {
		$fields = 't1.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::GETTER . SQLClient::FROM . '(' . 
					SQLClient::SELECT . self::MAILS . '.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::SENDER;
		$from = self::MAILS . SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . self::MAILS . '.' . 
				self::SENDER_ID . '=' . UsersService::USERS . '.' . UsersService::ID . ')' . SQLClient::SQL_AS . 't1' . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 't1.' . 
				self::GETTER_ID . '=' . UsersService::USERS . '.' . UsersService::ID;
		$where = self::HASH . "='" . $hash . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? 
					self::createDate($result, true, true) : 
						false;
		return $result[0];
	}
	
	/**
	 * function for sending mail
	 * @param string $subject subject of letter
	 * @param string $text text of letter
	 * @param integer $from sender
	 * @param integer $to getter
	 * @return mail id
	 */
	public static function sendMail($subject, $text, $from, $to, $plain){
		$date = gmdate ( "Y-m-d H:i:s" );
		$hash = md5($date . $from);
		$inbox = '3';
		$outbox = '4';
		#Insert new mail to DB
		$into = self::MAILS;
		$fields = self::SUBJECT . ', ' . self::TEXT . ', ' . self::SENDER_ID . ', ' . self::GETTER_ID . ', ' . 
					self::CRDATE . ', ' . self::HASH . ', ' . self::STATUS;
		$values = "'" . $subject . "','" . $text . "','" . $from . "','" . $to . "','" . $date . "','" . 
					$hash ."','" . $outbox . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		$date = gmdate ( "Y-m-d H:i:s" );
		$hash = md5($date . $from . $to);
		$fields = self::SUBJECT . ', ' . self::TEXT . ', ' . self::SENDER_ID . ', ' . self::GETTER_ID . ', ' . 
					self::CRDATE . ', ' . self::HASH . ', ' . self::STATUS;
		$values = "'" . $subject . "','" . $text . "','" . $from . "','" . $to . "','" . $date . "','" . 
					$hash ."','" . $inbox . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		
		$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/view-mail.html?id=' . $hash;
		$mail_to = UsersService::getUser($to);
		#call method for sending mail
		MailerService::replaceVars ( $mail_to[UsersService::EMAIL], null, $mail_to[UsersService::FIRSTNAME], $mail_to[UsersService::LASTNAME], $plain, $url );
		
		#get user id 
		$id = $result;
		return $result;
	}
	
	public static function deleteMail($hash){
		$mail = self::getMail($hash);
		$mail[self::STATUS] == 1 ? self::deleteFromTrash($hash) : self::goTrash($hash); 
	}
	
	public static function deleteMails($string){
		if (isset($string) && count($string)>0){
			$array = explode(',', $string);
			foreach ($array as $mail){
				self::deleteMail($mail);
			}
		}
	}
	
	/**
	 * delete mail from trash
	 * @param integer $id the mail id
	 */
	public static function deleteFromTrash($hash) {
		# setting the query variables
		$from = self::MAILS;
		$where = self::HASH . " = '" . $hash . "'";
		$orderBy = null;
		$limit = null;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
	}	

}

?>