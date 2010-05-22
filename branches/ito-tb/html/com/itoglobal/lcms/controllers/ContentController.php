<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	const RESULT = 'result';
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		//TODO: just an empty stub
		return $mvc;
	}
	
	public function handleTrainings($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		//TODO: just an empty stub
		return $mvc;
	}
	
	public function handleUsers($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		//TODO: just an empty stub
		return $mvc;
	}
	
	public function handleAdmins($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		//TODO: just an empty stub
		return $mvc;
	}
	
	
	public function handleLoginForm($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleRegistration($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	
	
	public function handleHelp($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	
	public function handleLoginFailed($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	
	public function handleAbout($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	
	public function handleEditCourse($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			$error = array ();
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$file = $_FILES ['file'];
				$path = 'storage/uploads/courses/' . $requestParams [CourseService::ALIAS] . "/avatar.jpg";
				$error [] .= ValidationService::checkAvatar ( $file );
				$error = array_filter ( $error );
			}
			if (count ( $error ) == 0) {
				if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
					StorageService::uploadFile ( $path, $file );
				}
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
			$fields = array ();
			$fields [] .= CourseService::CAPTION;
			$fields [] .= CourseService::DESCRIPTION;
			$fields [] .= CourseService::LEVEL;
			$fields [] .= CourseService::SCHOOL_ID;
			$vals = array ();
			$id = $requestParams [CourseService::ID];
			$vals [] .= $requestParams [CourseService::CAPTION];
			$vals [] .= $requestParams [CourseService::DESCRIPTION];
			$vals [] .= $requestParams [CourseService::LEVEL];
			$vals [] .= $requestParams [CourseService::SCHOOL_ID];
			CourseService::updateFields ( $id, $fields, $vals );
		}
		
		$where = CourseService::ID . " = '" . $requestParams [CourseService::ID] . "'";
		$result = CourseService::getCoursesList ( $where );
		isset ( $result [0] [CourseService::ID] ) ? $mvc->addObject ( CourseService::ID, $result [0] [CourseService::ID] ) : null;
		isset ( $result [0] [CourseService::CAPTION] ) ? $mvc->addObject ( CourseService::CAPTION, $result [0] [CourseService::CAPTION] ) : null;
		isset ( $result [0] [CourseService::DESCRIPTION] ) ? $mvc->addObject ( CourseService::DESCRIPTION, $result [0] [CourseService::DESCRIPTION] ) : null;
		isset ( $result [0] [CourseService::LEVEL] ) ? $mvc->addObject ( CourseService::LEVEL, $result [0] [CourseService::LEVEL] ) : null;
		isset ( $result [0] [CourseService::SCHOOL_ID] ) ? $mvc->addObject ( CourseService::SCHOOL_ID, $result [0] [CourseService::SCHOOL_ID] ) : null;
		isset ( $result [0] [CourseService::AVATAR] ) ? $mvc->addObject ( CourseService::AVATAR, $result [0] [CourseService::AVATAR] ) : null;
		isset ( $result [0] [CourseService::ALIAS] ) ? $mvc->addObject ( CourseService::ALIAS, $result [0] [CourseService::ALIAS] ) : null;
		return $mvc;
	}
	public function handleCourseDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		isset ( $requestParams [CourseService::REMOVE] ) ? ExerciseService::removeExercise ( $requestParams [CourseService::REMOVE], $requestParams [CourseService::ID] ) : null;
		isset ( $requestParams [CourseService::ADD] ) ? ExerciseService::addExercise ( $requestParams [CourseService::ADD], $requestParams [CourseService::ID] ) : null;
		
		$where = CourseService::ID . " = '" . $requestParams [CourseService::ID] . "'";
		$list = CourseService::getCoursesList ( $where );
		$mvc->addObject ( 'list', $list );
		
		#for admin
		$exerciseslist = ExerciseService::getExercisesList ();
		$mvc->addObject ( 'exerciseslist', $exerciseslist );
		
		#for users and visitor
		$where = ExerciseService::COURSE_ID . " = '" . $requestParams [ExerciseService::ID] . "'";
		$exerciselist = ExerciseService::getExercisesList ( $where );
		$mvc->addObject ( 'exerciselist', $exerciselist );
		
		return $mvc;
	}
	
	public function handleBrowseExercises($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		isset ( $requestParams [ExerciseService::DELETED] ) ? ExerciseService::deleteExercise ( $requestParams [ExerciseService::DELETED] ) : null;
		$list = ExerciseService::getExercisesList ();
		$mvc->addObject ( 'list', $list );
		return $mvc;
	}
	public function handleExerciseDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$where = ExerciseService::ID . " = '" . $requestParams [ExerciseService::ID] . "'";
		$list = ExerciseService::getExercisesList ( $where );
		$mvc->addObject ( 'list', $list );
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
		isset ( $requestParams [SchoolService::DELETED] ) ? SchoolService::deleteSchool ( $requestParams [SchoolService::DELETED] ) : null;
		$list = SchoolService::getSchoolsList ();
		$mvc->addObject ( 'list', $list );
		return $mvc;
	}
	public function handleNewSchool($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			//server-side validation
			$error = SchoolService::validation ( $requestParams, $_FILES );
			if (isset ( $error ) && count ( $error ) == 0) {
				StorageService::createDirectory ( 'storage/uploads/schools/' . $requestParams [SchoolService::ALIAS] );
				$path = 'storage/uploads/schools/' . $requestParams [SchoolService::ALIAS] . "/avatar.jpg";
				
				isset ( $_FILES ['file'] ) && $_FILES ['file'] ['error'] == 0 ? StorageService::uploadFile ( $path, $_FILES ['file'] ) : copy ( 'storage/uploads/default-school.jpg', $path );
				
				$fields = UsersService::ID;
				$from = UsersService::USERS;
				$where = UsersService::USERNAME . "='" . $requestParams [SchoolService::ADMIN] . "'";
				$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
				$admin = $result [0] [UsersService::ID];
				
				// Insert new school to DB
				$fields = SchoolService::ALIAS . ', ' . SchoolService::CAPTION . ', ' . SchoolService::DESCRIPTION . ', ' . SchoolService::AVATAR . ', ' . SchoolService::CRDATE . ', ' . SchoolService::FEE . ', ' . SchoolService::ADMIN;
				$owner_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$values = "'" . $requestParams [SchoolService::ALIAS] . "','" . $requestParams [SchoolService::CAPTION] . "','" . $requestParams [SchoolService::DESCRIPTION] . "','" . $path . "','" . gmdate ( "Y-m-d H:i:s" ) . "','0'," . $admin;
				$into = SchoolService::SCHOOLS_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
			//$mvc->addObject ( 'forward', 'successful' );
			//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		return $mvc;
	}
	public function handleEditSchool($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			$error = array ();
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$file = $_FILES ['file'];
				$path = 'storage/uploads/schools/' . $requestParams [SchoolService::ALIAS] . "/avatar.jpg";
				
				$error [] .= ValidationService::checkAvatar ( $file );
				$error = array_filter ( $error );
			}
			
			if (count ( $error ) == 0) {
				if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
					StorageService::uploadFile ( $path, $file );
				}
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
			$fields = array ();
			$fields [] .= SchoolService::CAPTION;
			$fields [] .= SchoolService::DESCRIPTION;
			$vals = array ();
			$id = $requestParams [SchoolService::ID];
			$vals [] .= $requestParams [SchoolService::CAPTION];
			$vals [] .= $requestParams [SchoolService::DESCRIPTION];
			SchoolService::updateFields ( $id, $fields, $vals );
		}
		
		$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
		$result = SchoolService::getSchoolsList ( $where );
		isset ( $result [0] [SchoolService::ID] ) ? $mvc->addObject ( SchoolService::ID, $result [0] [SchoolService::ID] ) : null;
		isset ( $result [0] [SchoolService::CAPTION] ) ? $mvc->addObject ( SchoolService::CAPTION, $result [0] [SchoolService::CAPTION] ) : null;
		isset ( $result [0] [SchoolService::DESCRIPTION] ) ? $mvc->addObject ( SchoolService::DESCRIPTION, $result [0] [SchoolService::DESCRIPTION] ) : null;
		isset ( $result [0] [SchoolService::AVATAR] ) ? $mvc->addObject ( SchoolService::AVATAR, $result [0] [SchoolService::AVATAR] ) : null;
		isset ( $result [0] [SchoolService::ALIAS] ) ? $mvc->addObject ( SchoolService::ALIAS, $result [0] [SchoolService::ALIAS] ) : null;
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
		isset ( $requestParams [ExerciseService::DELETED] ) ? ExerciseService::deleteExercise ( $requestParams [ExerciseService::DELETED] ) : null;
		$list = ExerciseService::getExercisesList ();
		$mvc->addObject ( 'list', $list );
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
		if (isset ( $requestParams ['submit'] )) {
			//server-side validation
			$error = ExerciseService::validation ( $requestParams );
			if (isset ( $error ) && count ( $error ) == 0) {
				/*print_r($_FILES);exit;
				if (isset ( $_FILES ['file'] ['name'] )) {
					$file = $_FILES ['file'];
					$path = 'storage/uploads/exercises/' . $_FILES ['file'] ['name'];
					StorageService::uploadFile ( $path, $file );
				}*/
				
				// Insert new exercise to DB
				$fields = ExerciseService::CAPTION . ', ' . ExerciseService::DESCRIPTION . ', ' . ExerciseService::OWNER . ', ' . ExerciseService::CRDATE . ', ' . ExerciseService::VIDEO;
				$owner_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$values = "'" . $requestParams [ExerciseService::CAPTION] . "','" . $requestParams [ExerciseService::DESCRIPTION] . "','" . $owner_id . "','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $requestParams [ExerciseService::VIDEO] . "'";
				$into = ExerciseService::EXERCISES_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
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
		if (isset ( $requestParams ['submit'] )) {
			//server-side validation
			$error = UsersService::validation ( $requestParams );
			if (count ( $error ) == 0) {
				// Insert new users to DB
				//UsersService::createUserDirectory($requestParams [UsersService::USERNAME]);
				

				StorageService::createDirectory ( 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] );
				StorageService::createDirectory ( 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] . '/profile' );
				StorageService::createDirectory ( 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] . '/trainings' );
				$path = 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] . '/profile/avatar.jpg';
				copy ( 'storage/uploads/default-avatar.jpg', $path );
				
				$fields = UsersService::USERNAME . ', ' . UsersService::FIRSTNAME . ', ' . UsersService::LASTNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . UsersService::CRDATE . ', ' . UsersService::VALIDATION . ', ' . UsersService::ENABLED . ', ' . UsersService::ROLE . ', ' . UsersService::AVATAR;
				$hash = md5 ( rand ( 1, 9999 ) );
				$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::FIRSTNAME] . "','" . $requestParams [UsersService::LASTNAME] . "','" . $requestParams [UsersService::EMAIL] . "','','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "','1','" . $requestParams [UsersService::ROLE] . "','" . $path . "'";
				$into = UsersService::USERS;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [UsersService::EMAIL] . '&validation_id=' . $hash;
				$plain = $mvc->getProperty ( 'template' );
				
				MailerService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $plain, $url );
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
		$error = array ();
		if (isset ( $requestParams ['submit'] )) {
			
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$file = $_FILES ['file'];
				$path = 'storage/uploads/users/' . $username . "/profile/avatar.jpg";
				$error [] .= ValidationService::checkAvatar ( $file );
			}
			
			if ($requestParams [UsersService::OLDPASSWORD] != null) {
				$error [] .= isset ( $requestParams [UsersService::PASSWORD] ) ? UsersService::checkPassword ( $requestParams [UsersService::PASSWORD] ) : false;
				$error [] .= isset ( $requestParams [UsersService::CONFIRM] ) ? UsersService::checkConfirmPassword ( $requestParams [UsersService::PASSWORD], $requestParams [UsersService::CONFIRM] ) : false;
			}
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
					StorageService::uploadFile ( $path, $file );
				}
				if ($requestParams [UsersService::OLDPASSWORD] != null) {
					$fields = UsersService::PASSWORD;
					$from = UsersService::USERS;
					$where = UsersService::ID . " = " . $requestParams [UsersService::ID];
					# executing the query
					$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
					//print_r($requestParams[UsersService::ID]); echo " "; print_r(md5($requestParams[UsersService::OLDPASSWORD])); exit;
					if (md5 ( $requestParams [UsersService::OLDPASSWORD] ) == $result [0] [UsersService::PASSWORD]) {
						$fields = array ();
						$fields [] .= UsersService::PASSWORD;
						$vals = array ();
						$vals [] .= md5 ( $requestParams [UsersService::PASSWORD] );
						UsersService::updateFields ( $id, $fields, $vals );
						$error [] = 'Your password successfully changed';
						$mvc->addObject ( UsersService::ERROR, $error );
					}
				}
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
			
			$fields = array ();
			$fields [] .= UsersService::FIRSTNAME;
			$fields [] .= UsersService::LASTNAME;
			$fields [] .= UsersService::EMAIL;
			$vals = array ();
			$vals [] .= $requestParams [UsersService::FIRSTNAME];
			$vals [] .= $requestParams [UsersService::LASTNAME];
			$vals [] .= $requestParams [UsersService::EMAIL];
			
			UsersService::updateFields ( $id, $fields, $vals );
		}
		
		$where = UsersService::ID . " = '" . $id . "'";
		$result = UsersService::getUsersList ( $where );
		isset ( $result [0] [UsersService::ID] ) ? $mvc->addObject ( UsersService::ID, $result [0] [UsersService::ID] ) : null;
		isset ( $result [0] [UsersService::AVATAR] ) ? $mvc->addObject ( UsersService::AVATAR, $result [0] [UsersService::AVATAR] ) : null;
		isset ( $result [0] [UsersService::LASTNAME] ) ? $mvc->addObject ( UsersService::LASTNAME, $result [0] [UsersService::LASTNAME] ) : null;
		isset ( $result [0] [UsersService::FIRSTNAME] ) ? $mvc->addObject ( UsersService::FIRSTNAME, $result [0] [UsersService::FIRSTNAME] ) : null;
		isset ( $result [0] [UsersService::EMAIL] ) ? $mvc->addObject ( UsersService::EMAIL, $result [0] [UsersService::EMAIL] ) : null;
		
		return $mvc;
	}
	public function handleSchoolAssigned($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		if (isset ( $requestParams [AssignedService::SIGNUP] )) {
			$school_id = $requestParams [AssignedService::SIGNUP];
			AssignedService::SignUpSchool ( $school_id, $user_id );
			$message = 'You succesfully signed up to this school';
		}
		
		if (isset ( $requestParams [AssignedService::SIGNOUT] )) {
			$school_id = $requestParams [AssignedService::SIGNOUT];
			AssignedService::SignOutSchool ( $school_id, $user_id );
			$message = 'You succesfully signed out from this school';
		}
		
		$mvc->addObject ( 'message', $message );
		
		return $mvc;
	}
}

?>