<?php

class SessionService {
	
	const LOCALE = 'locale';

	const USERS_ID = 'user_id';
	
	const USERNAME = 'username';
	
	const FIRSTNAME = 'firstname';
	
	const LASTNAME = 'lastname';
	
	const EMAIL = 'email';
	
	const ROLE = 'role';
	
	const ROLE_VR = 'VR';
	
	const ROLE_UR = 'UR';
	
	const ROLE_MR = 'MR';
	
	const ROLE_AR = 'AR';
	
	public static function startSession() {
		if (session_id () || session_start ()) {
			# check if session is new or existing one
			if (! isset ( $_SESSION ['timestamp'] )) {
				# security: clear any recovered data and regenerate ids
				session_regenerate_id ( true );
				$_SESSION ['timestamp'] = time ();
				//TODO: session storage is not completely removed after the session is closed. The root directory always resists
				# storage: setup session storage structure
				StorageService::initSessionStorage ( session_id () );
			}
		}
		return session_id ();
	}
	
	public static function getSessionId() {
		return (session_id () && session_id () != '') ? session_id () : self::startSession ();
	}
	
	public static function setAttribute($key, $value) {
		$_SESSION [$key] = $value;
	}
	
	public static function getAttribute($key) {
		$result = null;
		if (isset ( $_SESSION )) {
			$result = key_exists ( $key, $_SESSION ) ? $_SESSION [$key] : $result;
		}
		return $result;
	}
	
	public static function endSession() {
		if (session_id () || session_start ()) {
			# release all resources
			$sid = session_id ();
			StorageService::clearSessionStorage ( $sid );
			session_destroy ();
		}
	}
	
	public static function isLogedIn() {
		return self::getAttribute ( self::USERS_ID ) != null;
	}
	
	public static function getRole() {
		$result = self::getAttribute ( self::ROLE ); 
		return $result != null ? $result : self::ROLE_VR;
	}
	
	public static function setRole($role){
		self::setAttribute(self::ROLE, $role);
	} 
}
?>