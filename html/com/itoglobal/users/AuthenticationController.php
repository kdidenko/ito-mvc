<?php

require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class AuthenticationController extends BaseActionControllerImpl {
	
	/** User login handling controller method
	 * @param the $actionParams
	 * @param the $requestParams
	 * @return Object of ModelAndView
	 */
	public function login($actionParams, $requestParams) {
		$location = $this->onSuccess ( $actionParams );
		# setting the query variables
		$fields = UsersService::ID . ', ' . UsersService::USERNAME . ', ' . UsersService::FIRSTNAME . ', ' . UsersService::LASTNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . UsersService::ENABLED . ', ' . UsersService::DELETED . ', ' . UsersService::ROLE;
		$from = UsersService::USERS;
		$where = UsersService::USERNAME . " = '" . $requestParams [UsersService::USERNAME] . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		# authenticating user login
		

		if (count ( $result ) > 0 && $result [0] [UsersService::ENABLED] == 1 && $result [0] [UsersService::DELETED] == 0) {
			if (isset ( $requestParams [UsersService::PASSWORD] ) & $result [0] [UsersService::PASSWORD] == md5 ( $requestParams [UsersService::PASSWORD] )) {
				$id = $result [0] [UsersService::ID];
				$session = SessionService::startSession ();
				SessionService::setAttribute ( SessionService::USERS_ID, $id );
				SessionService::setAttribute ( SessionService::USERNAME, $result [0] [SessionService::USERNAME] );
				SessionService::setAttribute ( SessionService::FIRSTNAME, $result [0] [SessionService::FIRSTNAME] );
				SessionService::setAttribute ( SessionService::LASTNAME, $result [0] [SessionService::LASTNAME] );
				SessionService::setAttribute ( SessionService::EMAIL, $result [0] [SessionService::EMAIL] );
				SessionService::setRole ( $result [0] [UsersService::ROLE] );
				if (isset ( $requestParams ['persistent'] )) {
					$expire = time () + 60 * 60 * 24 * 7;
					setcookie ( "PHPSESSID", $session, $expire );
				}
			} else {
				$location = $this->onFailure ( $actionParams );
			}
		} else {
			$location = $this->onFailure ( $actionParams );
		}
		$this->forwardActionRequest ( $location );
	}
	
	public function logout($actionParams, $requestParams) {
		SessionService::endSession ();
		$location = $this->onSuccess ( $actionParams );
		$this->forwardActionRequest ( $location );
	}
}

?>