<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

/**
 * Authentication Controller
 * 	- implement user login/logout functionality
 * @author ITO-Global
 */
class AuthenticationController extends SecureActionControllerImpl {
	
	/** User login handling controller method
	 * @param the $actionParams
	 * @param the $requestParams
	 * @return Object of ModelAndView
	 */
	public function login($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams [UsersService::USERNAME]) && $requestParams [UsersService::USERNAME]!=NULL) {
			#get user which login
			$where = isset($requestParams [UsersService::USERNAME]) ? 
						UsersService::USERNAME . " = '" . $requestParams [UsersService::USERNAME] . "'" : 
							null;
			$result = UsersService::getUsersList($where);
			
			#checking user availble in DB, his enable and delete status
			if (count ( $result ) > 0 && $result [0] [UsersService::ENABLED] == 1 && $result [0] [UsersService::DELETED] == 0) {
				#checking password
				if (isset ( $requestParams [UsersService::PASSWORD] ) && $result [0] [UsersService::PASSWORD] == md5 ( $requestParams [UsersService::PASSWORD] )) {
					#save information to session
					$id = $result [0] [UsersService::ID];
					$session = SessionService::startSession ();
					SessionService::setAttribute ( SessionService::USERS_ID, $id );
					SessionService::setAttribute ( SessionService::FIRSTNAME, $result [0] [SessionService::FIRSTNAME] );
					SessionService::setAttribute ( SessionService::LASTNAME, $result [0] [SessionService::LASTNAME] );
					SessionService::setAttribute ( SessionService::EMAIL, $result [0] [SessionService::EMAIL] );
					SessionService::setRole ( $result [0] [UsersService::ROLE] );
					if ($result [0] [UsersService::ROLE] = UsersService::ROLE_TR) {
						SessionService::setAttribute ( SessionService::PLAN_ID, $result [0] [SessionService::PLAN_ID] );
					}
					SessionService::setAttribute ( SessionService::USERNAME, $result [0] [SessionService::USERNAME] );
					#if you select checkbox "Remember me"
					if (isset ( $requestParams ['persistent'] )) {
						$expire = time () + 60 * 60 * 24 * 7;
						setcookie ( "PHPSESSID", $session, $expire );
					}
					#forward to index page
					$location = $this->onSuccess ( $actionParams );
					$this->forwardActionRequest ( $location );
				} else {
					#back to login page
					$mvc->addObject ( UsersService::ERROR, '_i18n{Your entered incorrected password... Please try again.}' );
				}
			} else {
				#back to login page
				$mvc->addObject ( UsersService::ERROR, '_i18n{You entered incorrect username or your account is disable.}' );
			}
		}
		return $mvc;
	}
	
	/** User logout handling controller method
	 * @param the $actionParams
	 * @param the $requestParams
	 * @return Object of ModelAndView
	 */
	public function logout($actionParams, $requestParams) {
		SessionService::endSession ();
		$location = $this->onSuccess ( $actionParams );
		$this->forwardActionRequest ( $location );
	}
}

?>