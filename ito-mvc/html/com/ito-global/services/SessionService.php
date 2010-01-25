<?php

class SessionService {

	public static function startSession() {
		if(session_id() || session_start()){
			// check if session is new or existing one
			if(!isset($_SESSION['timestamp']))  {
				// security: clear any recovered data and regenerate ids
				session_regenerate_id(true);
				$_SESSION['timestamp'] = time();
				// storage: setup session storage structure
				StorageService::initSessionStorage( session_id() );
			}
		}
		return session_id();
	}

	public static function getSessionId() {
		return ( session_id() && session_id() != '') ? session_id() : self::startSession();
	}

	public static function setAttribute($key, $value){
		$_SESSION[$key] = $value;
	}

	public static function getAttribute($key){
		return  key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
	}

	public static function endSession() {
		if(session_id() || session_start()){
		    // release all resources
            $sid = session_id();
			StorageService::clearSessionStorage($sid);
			session_destroy();
		}
	}

}

?>