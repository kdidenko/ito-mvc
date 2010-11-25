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
	 * @var string defining the sender field name
	 */
	const SENDER = 'sender';
	/**
	 * @var string defining the getter field name
	 */
	const GETTER = 'getter';
	/**
	 * @var string defining the crdate field name
	 */
	const CRDATE = 'crdate';
	
	/**
	 * Retrieves the users mails by specified user id.
	 * @param integer $user the user id.
	 * @return mixed mails data or null if user with such id does not exists. 
	 */
	public static function getMails($user) {
		$fields = self::MAILS . '.*';
		$from = self::MAILS;
		$where = self::SENDER . '=' . $user;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	/**
	 * Retrieves mail by specified mail id.
	 * @param integer $id the mail id
	 * @return mail data
	 */
	public static function getMail($id) {
		$fields = self::MAILS . '.*';
		$from = self::MAILS;
		$where = self::ID . '=' . $id;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	/**
	 * function for sending mail
	 * @param string $subject subject of letter
	 * @param string $text text of letter
	 * @param integer $from sender
	 * @param integer $to getter
	 */
	public static function sendMail($subject, $text, $from, $to){
		#Insert new users to DB
		$fields = self::SUBJECT . ', ' . self::TEXT . ', ' . self::SENDER . ', ' . self::GETTER . ', ' . self::CRDATE;
		$values = "'" . $subject . "','" . $text . "','" . $from . "','" . $to . "','" . gmdate ( "Y-m-d H:i:s" ) . "'";
		$into = self::MAILS;
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		#get user id 
		$id = $result;
	}
	
	/**
	 * delete mail from DB
	 * @param integer $id the mail id
	 */
	public static function deleteMail($id) {
		# setting the query variables
		$from = self::MAILS;
		$where = self::ID . " = '" . $id . "'";
		$orderBy = null;
		$limit = null;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
	}	

}

?>