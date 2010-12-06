<?php

class AssignedService {
	
	const SCHOOLS_ASSIGNED = 'schools_assigned';
	const ID = 'id';
	const USER_ID = 'user_id';
	const SCHOOL_ID = 'school_id';
	const SIGNOUT = 'signout';
	const SIGNUP = 'signup';
		
	public static function SignUpSchool($school_id, $user_id){
		$fields = self::USER_ID . ', ' . self::SCHOOL_ID;
		$values = "'" . $user_id . "','" . $school_id . "'";
		$into = self::SCHOOLS_ASSIGNED;
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	
	public static function SignOutSchool($school_id, $user_id){
		# setting the query variables
		$from = self::SCHOOLS_ASSIGNED;
		$where = self::SCHOOL_ID . " = '" . $school_id . "' AND " . self::USER_ID . " = '" . $user_id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete ( $from, $where, '', '' );
	}
}

?>