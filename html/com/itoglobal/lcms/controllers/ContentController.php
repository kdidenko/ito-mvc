<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	const RESULT = 'result';
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$firstname = SessionService::getAttribute ( SessionService::FIRSTNAME );
		isset ( $firstname ) ? $mvc->addObject ( SessionService::FIRSTNAME, $firstname ) : null;
		$lastname = SessionService::getAttribute ( SessionService::LASTNAME );
		isset ( $lastname ) ? $mvc->addObject ( SessionService::LASTNAME, $lastname ) : null;
		return $mvc;
	}
	public function handleHelp($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleSchools($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleNewSchool($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}	
	public function handleTrainings($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleCommunity($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleDiscussions($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleAbout($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleMyResponses($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleValuateResponses($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleBrowseExercises($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		isset ( $requestParams [ExerciseService::DELETED] ) ? ExerciseService::deleteExercise ( $requestParams [ExerciseService::DELETED]) : null;
		$list = ExerciseService::getExercisesList();
		$mvc->addObject('list', $list);		
		return $mvc;
	}
	public function handleExerciseDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		isset ( $requestParams [ExerciseService::DELETED] ) ? ExerciseService::deleteExercise ( $requestParams [ExerciseService::DELETED]) : null;
		$where = ExerciseService::ID . " = '" . $requestParams [ExerciseService::ID] . "'";
		$list = ExerciseService::getExercisesList($where);
		$mvc->addObject('list', $list);		
		return $mvc;
	}
	public function handleMyChallenges($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleMessages($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleManageSchools($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$list = SchoolService::getSchoolsList();
		$mvc->addObject('list', $list);		
		return $mvc;
	}
	public function handleManageExercises($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset ( $requestParams ['submit'] )) {
			$fields = array ();
			$fields [] .= ExerciseService::CAPTION;
			$fields [] .= ExerciseService::DESCRIPTION;
			$vals = array ();
			$id = $requestParams [ExerciseService::ID];
			$vals [] .= $requestParams [ExerciseService::CAPTION];
			$vals [] .= $requestParams [ExerciseService::DESCRIPTION];
			ExerciseService::updateFields ( $id, $fields, $vals );
		}
		
		
		isset ( $requestParams [ExerciseService::DELETED] ) ? ExerciseService::deleteExercise ( $requestParams [ExerciseService::DELETED]) : null;
		$list = ExerciseService::getExercisesList();
		$mvc->addObject('list', $list);		
		return $mvc;
	}
	public function handleEditExercise($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$where = ExerciseService::ID . " = '" . $requestParams [ExerciseService::ID] . "'";
		$result = ExerciseService::getExercisesList ( $where );
		isset ( $result [0] [ExerciseService::ID] ) ? $mvc->addObject ( ExerciseService::ID, $result [0] [ExerciseService::ID] ) : null;
		isset ( $result [0] [ExerciseService::CAPTION] ) ? $mvc->addObject ( ExerciseService::CAPTION, $result [0] [ExerciseService::CAPTION] ) : null;
		isset ( $result [0] [ExerciseService::DESCRIPTION] ) ? $mvc->addObject ( ExerciseService::DESCRIPTION, $result [0] [ExerciseService::DESCRIPTION] ) : null;
		
		return $mvc;
	}
	
	public function handleNewExercise($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams ['submit'])) {
			//server-side validation
			$error = ExerciseService::validation ( $requestParams );
			if (isset($error) && count ( $error ) == 0) {
				// Insert new exercise to DB
				/*StorageService::createDirectory ( 'storage/uploads/' . $requestParams [UsersService::USERNAME] );
				StorageService::createDirectory ( 'storage/uploads/' . $requestParams [UsersService::USERNAME] . '/profile' );
				StorageService::createDirectory ( 'storage/uploads/' . $requestParams [UsersService::USERNAME] . '/trainings' );
				$path = 'storage/uploads/' . $requestParams [UsersService::USERNAME] . '/profile/avatar.jpg';*/
								
				$fields = ExerciseService::CAPTION . ', ' . ExerciseService::DESCRIPTION . ', ' . ExerciseService::OWNER . ', ' . ExerciseService::CRDATE;
				//$hash = md5 ( rand ( 1, 9999 ) );
				$owner_id = SessionService::getAttribute(SessionService::USERS_ID);
				$values = "'" . $requestParams [ExerciseService::CAPTION] . "','" . $requestParams [ExerciseService::DESCRIPTION] . "','" . $owner_id . "','" . gmdate ( "Y-m-d H:i:s" ) . "'";
				$into = ExerciseService::EXERCISES_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				//$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [UsersService::EMAIL] . '&validation_id=' . $hash;
				//$plain = $mvc->getProperty('template');

				//MailersService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $plain, $url);
				$mvc->addObject ( 'forward', 'successful' );
				//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		return $mvc;
	}
	
	public function handleManageUsers($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			$fields = array ();
			$fields [] .= UsersService::USERNAME;
			$fields [] .= UsersService::FIRSTNAME;
			$fields [] .= UsersService::LASTNAME;
			$fields [] .= UsersService::EMAIL;
			$fields [] .= UsersService::ENABLED;
			$fields [] .= UsersService::ROLE;
			$vals = array ();
			$id = $requestParams [UsersService::ID];
			$vals [] .= $requestParams [UsersService::USERNAME];
			$vals [] .= $requestParams [UsersService::FIRSTNAME];
			$vals [] .= $requestParams [UsersService::LASTNAME];
			$vals [] .= $requestParams [UsersService::EMAIL];
			$vals [] .= $requestParams [UsersService::ENABLED];
			$vals [] .= $requestParams [UsersService::ROLE];
			UsersService::updateFields ( $id, $fields, $vals );
		}
		isset ( $requestParams [UsersService::ENABLED] ) ? UsersService::updateFields ( $requestParams [UsersService::ENABLED], UsersService::ENABLED, '1' ) : '';
		isset ( $requestParams [UsersService::DISABLE] ) ? UsersService::updateFields ( $requestParams [UsersService::DISABLE], UsersService::ENABLED, '0' ) : '';
		isset ( $requestParams [UsersService::DELETED] ) ? UsersService::updateFields ( $requestParams [UsersService::DELETED], UsersService::DELETED, '1' ) : '';
		$where = UsersService::DELETED . " = 0";
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		isset ( $id ) ? $where .= " and " . UsersService::ID . "!=" . $id : '';
		$result = UsersService::getUsersList ( $where );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		
		return $mvc;
	}
	
	public function handleEditUser($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$where = UsersService::ID . " = '" . $requestParams [UsersService::ID] . "'";
		$result = UsersService::getUsersList ( $where );
		isset ( $result [0] [UsersService::ID] ) ? $mvc->addObject ( UsersService::ID, $result [0] [UsersService::ID] ) : null;
		isset ( $result [0] [UsersService::USERNAME] ) ? $mvc->addObject ( UsersService::USERNAME, $result [0] [UsersService::USERNAME] ) : null;
		isset ( $result [0] [UsersService::LASTNAME] ) ? $mvc->addObject ( UsersService::LASTNAME, $result [0] [UsersService::LASTNAME] ) : null;
		isset ( $result [0] [UsersService::FIRSTNAME] ) ? $mvc->addObject ( UsersService::FIRSTNAME, $result [0] [UsersService::FIRSTNAME] ) : null;
		isset ( $result [0] [UsersService::EMAIL] ) ? $mvc->addObject ( UsersService::EMAIL, $result [0] [UsersService::EMAIL] ) : null;
		isset ( $result [0] [UsersService::ENABLED] ) ? $mvc->addObject ( UsersService::ENABLED, $result [0] [UsersService::ENABLED] ) : null;
		isset ( $result [0] [UsersService::DELETED] ) ? $mvc->addObject ( UsersService::DELETED, $result [0] [UsersService::DELETED] ) : null;
		isset ( $result [0] [UsersService::ROLE] ) ? $mvc->addObject ( UsersService::ROLE, $result [0] [UsersService::ROLE] ) : null;
		
		return $mvc;
	}
	
	public function handleNewUser($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams ['submit'])) {
			//server-side validation
			$error = UsersService::validation ( $requestParams );
			if (count ( $error ) == 0) {
				// Insert new users to DB
				StorageService::createDirectory ( 'storage/uploads/' . $requestParams [UsersService::USERNAME] );
				StorageService::createDirectory ( 'storage/uploads/' . $requestParams [UsersService::USERNAME] . '/profile' );
				StorageService::createDirectory ( 'storage/uploads/' . $requestParams [UsersService::USERNAME] . '/trainings' );
				$path = 'storage/uploads/' . $requestParams [UsersService::USERNAME] . '/profile/avatar.jpg';
				copy ( 'storage/uploads/default-avatar.jpg', $path );
				
				$fields = UsersService::USERNAME . ', ' . UsersService::FIRSTNAME . ', ' . UsersService::LASTNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . UsersService::CRDATE . ', ' . UsersService::VALIDATION . ', ' . UsersService::ENABLED . ', ' . UsersService::ROLE . ', ' . UsersService::AVATAR;
				$hash = md5 ( rand ( 1, 9999 ) );
				$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::FIRSTNAME] . "','" . $requestParams [UsersService::LASTNAME] . "','" . $requestParams [UsersService::EMAIL] . "','','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "','1','" . $requestParams[UsersService::ROLE] . "','" . $path . "'";
				$into = UsersService::USERS;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [UsersService::EMAIL] . '&validation_id=' . $hash;
				$plain = $mvc->getProperty('template');

				//MailersService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $plain, $url);
				$mvc->addObject ( 'forward', 'successful' );
				//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		return $mvc;
	}
	
	public function handleMyProfile($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$username = SessionService::getAttribute ( UsersService::USERNAME );
		if (isset ( $_FILES ['file'] )) {
			$file = $_FILES ['file'];
			$path = 'storage/uploads/' . $username . "/profile/avatar.jpg";
			
			$size = getimagesize ( $file ['tmp_name'] );
			if ($size [0] <= 80 && $size [1] <= 80) {
				StorageService::uploadFile ( $path, $file );
			}
			
			$fields = array ();
			$fields [] .= UsersService::FIRSTNAME;
			$fields [] .= UsersService::LASTNAME;
			$fields [] .= UsersService::EMAIL;
			$fields [] .= UsersService::AVATAR;
			$vals = array ();
			$vals [] .= $requestParams [UsersService::FIRSTNAME];
			$vals [] .= $requestParams [UsersService::LASTNAME];
			$vals [] .= $requestParams [UsersService::EMAIL];
			$vals [] .= $path;
			UsersService::updateFields ( $id, $fields, $vals );
		}
		
		$where = UsersService::ID . " = '" . $id . "'";
		$result = UsersService::getUsersList ( $where );
		
		isset ( $result [0] [UsersService::AVATAR] ) ? $mvc->addObject ( UsersService::AVATAR, $result [0] [UsersService::AVATAR] ) : null;
		isset ( $result [0] [UsersService::LASTNAME] ) ? $mvc->addObject ( UsersService::LASTNAME, $result [0] [UsersService::LASTNAME] ) : null;
		isset ( $result [0] [UsersService::FIRSTNAME] ) ? $mvc->addObject ( UsersService::FIRSTNAME, $result [0] [UsersService::FIRSTNAME] ) : null;
		isset ( $result [0] [UsersService::EMAIL] ) ? $mvc->addObject ( UsersService::EMAIL, $result [0] [UsersService::EMAIL] ) : null;
		
		return $mvc;
	}
}

?>