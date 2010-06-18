<?php

require_once 'com/itoglobal/lcms/controllers/ContentController.php';

class ModeratorContentController extends ContentController {
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#for all
		$firstname = SessionService::getAttribute ( SessionService::FIRSTNAME );
		isset ( $firstname ) ? $mvc->addObject ( SessionService::FIRSTNAME, $firstname ) : null;
		$lastname = SessionService::getAttribute ( SessionService::LASTNAME );
		isset ( $lastname ) ? $mvc->addObject ( SessionService::LASTNAME, $lastname ) : null;
		
		#for moderator
			$id = SessionService::getAttribute(SessionService::USERS_ID);
			$where = SchoolService::ADMIN . " = '" . $id . "'";
			$mrSchList = SchoolService::getSchoolsList ($where);
			$mvc->addObject ( 'mrSchList', $mrSchList );
			
		return $mvc;
	}
	
	public function handleSchoolDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );

		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$where = SchoolService::ADMIN . " = '" . $id . "'";
		$mrSchList = SchoolService::getSchoolsList ($where);
		if (isset($requestParams[SchoolService::ID])){
			if ($mrSchList[0][SchoolService::ID] == $requestParams[SchoolService::ID]){	
			
				#moderator
				isset ( $requestParams [CourseService::REMOVE] ) ? CourseService::removeCourse ( $requestParams [CourseService::REMOVE], $requestParams [CourseService::ID] ) : null;
				isset ( $requestParams [CourseService::ADD] ) ? CourseService::addCourse ( $requestParams [CourseService::ADD], $requestParams [CourseService::ID]  ) : null;		
				
				#for all
				$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
				$list = SchoolService::getSchoolsList ( $where );
				$mvc->addObject ( 'list', $list );
				
				#for moderator
				$courseslist = CourseService::getCoursesList ();
				$mvc->addObject ( 'courseslist', $courseslist );
			}
		}
		return $mvc;
	}
	
	public function handleEditSchool($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$where = SchoolService::ADMIN . " = '" . $id . "'";
		$mrSchList = SchoolService::getSchoolsList ($where);
		
		if (isset($requestParams[SchoolService::ID])){
			if ($mrSchList[0][SchoolService::ID] == $requestParams[SchoolService::ID]){	
				#moderator list for admin
				$where = UsersService::ROLE . "= '" . UsersService::ROLE_MR . "'" ;
				$mrList = UsersService::getUsersList($where);
				$mvc->addObject ( 'mrList', $mrList );
				
				if (isset ( $requestParams ['submit'] )) {
					$error = array ();
					if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
						$file = $_FILES ['file'];
						$path = 'storage/uploads/schools/' . $requestParams [SchoolService::ALIAS] . "/avatar.jpg";
						
						$error[] .= ValidationService::checkAvatar ( $file );
						$error = array_filter ( $error );
					}
					
					if (count ( $error ) == 0) {
						if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
							StorageService::uploadFile ( $path, $file );
						}
					}else{
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
			}
		}
		return $mvc;
	}
	
	public function handleManageCourses($actionParams, $requestParams) {		
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#for moderator
		isset ( $requestParams [CourseService::DELETED] ) ? CourseService::deleteCourse ( $requestParams [CourseService::DELETED] ) : null;
		
		#for moderator
		$list = CourseService::getCoursesList();
		$mvc->addObject ( 'list', $list );
		return $mvc;
	}
	
	public function handleNewCourse($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			//server-side validation
			$error = CourseService::validation ( $requestParams,  $_FILES );
			if (isset ( $error ) && count ( $error ) == 0) {
				StorageService::createDirectory ( 'storage/uploads/courses/' . $requestParams [CourseService::ALIAS] );
				$path = 'storage/uploads/courses/' . $requestParams [CourseService::ALIAS] . "/avatar.jpg";
				isset ( $_FILES ['file'] ) && $_FILES ['file'] ['error'] == 0 ?
					StorageService::uploadFile ( $path, $_FILES ['file'] ) :
						copy ( 'storage/uploads/default-course.jpg', $path );
				// Insert new school to DB
				$fields = CourseService::LEVEL . ', ' . CourseService::CAPTION . ', ' . CourseService::DESCRIPTION . ', ' . CourseService::ALIAS . ', ' . CourseService::AVATAR . ', ' . CourseService::CRDATE . ', ' . CourseService::FEE . ', ' . CourseService::SCHOOL_ID;
				$owner_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$values = "'" . $requestParams [CourseService::LEVEL] . "','" . $requestParams [CourseService::CAPTION] . "','" . $requestParams [CourseService::DESCRIPTION] . "','" . $requestParams [CourseService::ALIAS] . "','" . $path . "','" . gmdate ( "Y-m-d H:i:s" ) . "','0','0'";
				$into = CourseService::COURSE_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				//$mvc->addObject ( 'forward', 'successful' );
				//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		return $mvc;
	}
	public function handleEditCourse($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			$error = array ();
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$file = $_FILES ['file'];
				$path = 'storage/uploads/courses/' . $requestParams [CourseService::ALIAS] . "/avatar.jpg";
				$error[] .= ValidationService::checkAvatar ( $file );
				$error = array_filter ( $error );
			}
			if (count ( $error ) == 0) {
				if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
					StorageService::uploadFile ( $path, $file );
				}
			}else{
				$mvc->addObject ( UsersService::ERROR, $error );
			}
			$fields = array ();
			$fields [] .= CourseService::CAPTION;
			$fields [] .= CourseService::DESCRIPTION;
			$fields [] .= CourseService::LEVEL;
			$vals = array ();
			$id = $requestParams [CourseService::ID];
			$vals [] .= $requestParams [CourseService::CAPTION];
			$vals [] .= $requestParams [CourseService::DESCRIPTION];
			$vals [] .= $requestParams [CourseService::LEVEL];
			CourseService::updateFields ( $id, $fields, $vals );
		}
		
		$where = CourseService::ID . " = '" . $requestParams [CourseService::ID] . "'";
		$result = CourseService::getCoursesList ( $where );
		isset ( $result [0] [CourseService::ID] ) ? $mvc->addObject ( CourseService::ID, $result [0] [CourseService::ID] ) : null;
		isset ( $result [0] [CourseService::CAPTION] ) ? $mvc->addObject ( CourseService::CAPTION, $result [0] [CourseService::CAPTION] ) : null;
		isset ( $result [0] [CourseService::DESCRIPTION] ) ? $mvc->addObject ( CourseService::DESCRIPTION, $result [0] [CourseService::DESCRIPTION] ) : null;
		isset ( $result [0] [CourseService::LEVEL] ) ? $mvc->addObject ( CourseService::LEVEL, $result [0] [CourseService::LEVEL] ) : null;
		isset ( $result [0] [CourseService::AVATAR] ) ? $mvc->addObject ( CourseService::AVATAR, $result [0] [CourseService::AVATAR] ) : null;
		isset ( $result [0] [CourseService::ALIAS] ) ? $mvc->addObject ( CourseService::ALIAS, $result [0] [CourseService::ALIAS] ) : null;
		return $mvc;
	}
	public function handleCourseDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#for moderator - add exercise to course
		isset ( $requestParams [CourseService::REMOVE] ) ? ExerciseService::removeExercise ( $requestParams [CourseService::REMOVE], $requestParams [CourseService::ID] ) : null;
		isset ( $requestParams [CourseService::ADD] ) ? ExerciseService::addExercise ( $requestParams [CourseService::ADD], $requestParams [CourseService::ID]  ) : null;		

		#for moderator
		$where = CourseService::ID . " = '" . $requestParams [CourseService::ID] . "'";
		$list = CourseService::getCoursesList ( $where );
		$mvc->addObject ( 'list', $list );
		
		#for moderator
		$exerciseslist = ExerciseService::getExercisesList();
		$mvc->addObject ( 'exerciseslist', $exerciseslist );
		
		return $mvc;
	}
	
	//TODO: IF this need, remove this method! (course-details: delete exercises)
	public function handleManageExercises($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		/*if (isset ( $requestParams ['submit'] )) {
			$fields = array ();
			$fields [] .= ExerciseService::CAPTION;
			$fields [] .= ExerciseService::DESCRIPTION;
			$vals = array ();
			$id = $requestParams [ExerciseService::ID];
			$vals [] .= $requestParams [ExerciseService::CAPTION];
			$vals [] .= $requestParams [ExerciseService::DESCRIPTION];
			ExerciseService::updateFields ( $id, $fields, $vals );
		}*/
		isset ( $requestParams [ExerciseService::DELETED] ) ? ExerciseService::deleteExercise ( $requestParams [ExerciseService::DELETED] ) : null;
		$list = ExerciseService::getExercisesList ();
		$mvc->addObject ( 'list', $list );
		return $mvc;
	}
	

	public function handleEditExercise($actionParams, $requestParams) {
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
		#for admin and moderator
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
		isset ( $id ) ? $where .= " and " . UsersService::ID . "!=" . $id . ' and ' . UsersService::ROLE . " != '" . UsersService::ROLE_AR . "' OR '" . UsersService::ROLE_MR . "'" : '';
		$result = UsersService::getUsersList ( $where );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		
		return $mvc;
	}
	
	public function handleEditUser($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		#for admin and moderator
		if (isset($requestParams[UsersService::ID])){
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
		}
		return $mvc;
	}
	
	public function handleNewUser($actionParams, $requestParams) {
		#for admin and moderator
		//calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			//server-side validation
			$error = UsersService::validation ( $requestParams );
			if (count ( $error ) == 0) {
				// Insert new users to DB
				//UsersService::createUserDirectory($requestParams [UsersService::USERNAME]);
				
				StorageService::createDirectory ( 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] );
				StorageService::createDirectory ( 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] . '/profile' );
				StorageService::createDirectory ( 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] . '/courses' );
				$path = 'storage/uploads/users/' . $requestParams [UsersService::USERNAME] . '/profile/avatar.jpg';
				copy ( 'storage/uploads/default-avatar.jpg', $path );
				
				$fields = UsersService::USERNAME . ', ' . UsersService::FIRSTNAME . ', ' . UsersService::LASTNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . UsersService::CRDATE . ', ' . UsersService::VALIDATION . ', ' . UsersService::ENABLED . ', ' . UsersService::ROLE . ', ' . UsersService::AVATAR;
				$hash = md5 ( rand ( 1, 9999 ) );
				$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::FIRSTNAME] . "','" . $requestParams [UsersService::LASTNAME] . "','" . $requestParams [UsersService::EMAIL] . "','','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "','1','" . $requestParams [UsersService::ROLE] . "','" . $path . "'";
				$into = UsersService::USERS;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [UsersService::EMAIL] . '&validation_id=' . $hash;
				$plain = $mvc->getProperty ( 'template' );
				
				MailerService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $plain, $url);
				$mvc->addObject ( 'forward', 'successful' );
				//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		return $mvc;
	}
	
	public function handleManageCategories($actionParams, $requestParams) {
	$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset ( $requestParams ['submit'] )) {
			$fields = array ();
			$fields [] .= CategoriesService::NAME;
			$vals = array ();
			$id = $requestParams [CategoriesService::ID];
			$vals [] .= $requestParams [CategoriesService::NAME];
			CategoriesService::updateFields ( $id, $fields, $vals );
		}
		isset ( $requestParams [UsersService::DELETED] ) ? CategoriesService::deleteCategories ( $requestParams [UsersService::DELETED]) : '';
		
		$result = CategoriesService::getCategoriesList ();
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		return $mvc;
	}
	public function handleNewCategory($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset ( $requestParams ['submit'] )) {
			if ($requestParams[CategoriesService::NAME] != NULL) {
				// Insert new categories to DB
				$fields = CategoriesService::NAME;
				$values = "'" . $requestParams[CategoriesService::NAME] . "'";
				$into = CategoriesService::CATEGORIES_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				$mvc->addObject ( 'forward', 'successful' );
				//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} 
		}
		return $mvc;
	}
	public function handleEditCategory($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset($requestParams[CategoriesService::ID])){
			$where = CategoriesService::ID . " = '" . $requestParams [CategoriesService::ID] . "'";
			$result = CategoriesService::getCategoriesList ( $where );
			isset ( $result [0] [CategoriesService::ID] ) ? $mvc->addObject ( CategoriesService::ID, $result [0] [CategoriesService::ID] ) : null;
			isset ( $result [0] [CategoriesService::NAME] ) ? $mvc->addObject ( CategoriesService::NAME, $result [0] [CategoriesService::NAME] ) : null;
		}
			
		return $mvc;
	}
}

?>