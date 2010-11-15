<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class ModeratorContentController extends ContentController {
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		return $mvc;
	}
	
	public function handleSchoolDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );

		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$where = SchoolService::ADMIN . " = '" . $id . "'";
		$mrSchList = SchoolService::getSchoolsList ($where);
		if (isset($requestParams[SchoolService::ID])){
			foreach($mrSchList as $key => $value){
				if ($value[SchoolService::ID] == $requestParams[SchoolService::ID]){	
				
					#get school list
					$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
					$list = SchoolService::getSchoolsList ( $where );
					$mvc->addObject ( 'list', $list [0]);
					
					#get course list
					$where = CourseService::COURSE_TABLE . '.' . CourseService::SCHOOL_ID . " = '" . $requestParams [CourseService::ID] . "'";
					$courseslist = CourseService::getCoursesList ($where);
					$mvc->addObject ( 'courseslist', $courseslist );
				}
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
			foreach($mrSchList as $key => $value){
				if ($value[SchoolService::ID] == $requestParams[SchoolService::ID]){	
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
						$mvc->addObject ( 'forward', 'successful' );
					}
					
					$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
					$result = SchoolService::getSchoolsList ( $where );
					$mvc->addObject ( self::RESULT, $result [0] );
				}
			}
		}
		return $mvc;
	}
	
	public function handleManageCourses($actionParams, $requestParams) {		
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		/*
		#if no school assigne to moderator than page forward to index.html
		$schAssign = self::hasSchlAssign();
		if($schAssign == NULL){ 
			$location = $this->onFailure ( $actionParams );
			$this->forwardActionRequest ( $location );	
		}*/
		
		#remove course
		isset ( $requestParams [CourseService::DELETED] ) ? CourseService::deleteCourse ( $requestParams [CourseService::DELETED] ) : null;
		
		#get courses only for 1 category
		$where = isset ($requestParams [CourseService::ID] ) ? CourseService::CATEGORY_ID . '=' . $requestParams [CourseService::ID] . " AND " : NULL ; 
		#get courses list (assigned to moderator) for creating new exercise
		$list = CourseService::getAccessMRcourses($where);
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
				
				# Insert new school to DB
				$fields = CourseService::CATEGORY_ID . ', ' . CourseService::CAPTION . ', ' . CourseService::DESCRIPTION . ', ' . CourseService::ALIAS . ', ' . CourseService::AVATAR . ', ' . CourseService::CRDATE . ', ' . CourseService::BASE_FEE . ', ' . CourseService::SCHOOL_ID;
				$owner_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$values = "'" . $requestParams [CourseService::CATEGORY_ID] . "','" . $requestParams [CourseService::CAPTION] . "','" . $requestParams [CourseService::DESCRIPTION] . "','" . $requestParams [CourseService::ALIAS] . "','" . $path . "','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $requestParams [CourseService::BASE_FEE] . "', '" . $requestParams [CourseService::SCHOOL_ID] . "'";
				$into = CourseService::COURSE_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				$mvc->addObject ( 'forward', 'successful' );
				//$mvc->addObject ( 'forward', 'successful' );
				//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		#get categories
		$mrCategList = CategoriesService::getCategoriesList ();
		$mvc->addObject ( 'mrCategList' , $mrCategList );
		#get schools
		$mrSchList = SchoolService::getMrSchools();
		$mvc->addObject ( 'mrSchList' , $mrSchList );
		
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
			$fields [] .= CourseService::BASE_FEE;
			$fields [] .= CourseService::CATEGORY_ID;
			$vals = array ();
			$id = $requestParams [CourseService::ID];
			$vals [] .= $requestParams [CourseService::CAPTION];
			$vals [] .= $requestParams [CourseService::DESCRIPTION];
			$vals [] .= $requestParams [CourseService::BASE_FEE];
			$vals [] .= $requestParams [CourseService::CATEGORY_ID];
			CourseService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( 'forward', 'successful' );
		}
		
		if (isset ($requestParams [CourseService::ID])){
			$where = CourseService::COURSE_TABLE . '.' . CourseService::ID . " = '" . $requestParams [CourseService::ID] . "'";
			$result = CourseService::getCoursesList ( $where );
			$mvc->addObject ( self::RESULT, $result [0]);
			#get categories
			$mrCategList = CategoriesService::getCategoriesList ();
			$mvc->addObject ( 'mrCategList' , $mrCategList );
		}
		
		return $mvc;
	}
	public function handleCourseDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		/*
		#delete courses
		isset ( $requestParams [ExerciseService::DELETED] ) ? ExerciseService::deleteExercise ( $requestParams [ExerciseService::DELETED] ) : null;
		isset ( $requestParams [CourseService::ADD] ) ? ExerciseService::addExercise ( $requestParams [CourseService::ADD], $requestParams [CourseService::ID]  ) : null;		
		*/
		if (isset($requestParams[CourseService::ID])){
			#get courses list
			$where = CourseService::COURSE_TABLE . '.' . CourseService::ID . " = '" . $requestParams [CourseService::ID] . "'";
			$list = CourseService::getCoursesList ( $where );
			$mvc->addObject ( 'list', $list [0]);
			
			#get exercises list
			$where = ExerciseService::COURSE_ID . " = '" . $requestParams [CourseService::ID] . "'";
			$exerciseslist = ExerciseService::getExercisesList($where);
			$mvc->addObject ( 'exerciseslist', $exerciseslist );
		}
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
		isset ( $result ) ?	$mvc->addObject ( 'exrList', $result[0] ) : NULL;
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
				$fields = ExerciseService::CAPTION . ', ' . ExerciseService::DESCRIPTION . ', ' . 
							ExerciseService::OWNER . ', ' . ExerciseService::CRDATE . ', ' . 
							ExerciseService::VIDEO . ', ' . ExerciseService::COURSE_ID;
				$owner_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$values = "'" . $requestParams [ExerciseService::CAPTION] . "','" . 
							$requestParams [ExerciseService::DESCRIPTION] . "','" . $owner_id . "','" . 
							gmdate ( "Y-m-d H:i:s" ) . "','" . $requestParams [ExerciseService::VIDEO]
							 . "','" . $requestParams [ExerciseService::COURSE_ID] . "'";
				$into = ExerciseService::EXERCISES_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				$mvc->addObject ( 'forward', 'successful' );
				//$this->forwardActionRequest ( $mvc->getProperty('onsuccess') );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		
		//TODO: do method from this
		#get courses list (assigned to moderator) for creating new exercise
		$user_id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = CourseService::COURSE_TABLE . "." . CourseService::ID . ", " . 
					CourseService::COURSE_TABLE . "." . CourseService::CAPTION;
		$from = CourseService::COURSE_TABLE . SQLClient::JOIN . SchoolService::SCHOOLS_TABLE . 
				SQLClient::ON . SchoolService::SCHOOLS_TABLE . "." . SchoolService::ID . "=" .
				CourseService::COURSE_TABLE . "." . CourseService::SCHOOL_ID;
		$where = SchoolService::ADMIN . " = '" . $user_id . "'";
		# executing query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : null;
		$mvc->addObject ( 'courseList', $result );
		return $mvc;
	}
	
	public function handleManageUsers($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		/*
		$schAssign = self::hasSchlAssign();
		if($schAssign == NULL){ 
			$location = $this->onFailure ( $actionParams );
			$this->forwardActionRequest ( $location );
		}
		*/
		#edit user
		if (isset ( $requestParams ['submit'] )) {
			$fields = array ();
			$fields [] .= UsersService::USERNAME;
			$fields [] .= UsersService::FIRSTNAME;
			$fields [] .= UsersService::LASTNAME;
			$fields [] .= UsersService::EMAIL;
			$fields [] .= UsersService::ENABLED;
			$vals = array ();
			$id = $requestParams [UsersService::ID];
			$vals [] .= $requestParams [UsersService::USERNAME];
			$vals [] .= $requestParams [UsersService::FIRSTNAME];
			$vals [] .= $requestParams [UsersService::LASTNAME];
			$vals [] .= $requestParams [UsersService::EMAIL];
			$vals [] .= $requestParams [UsersService::ENABLED];
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( 'forward', 'successful' );
		}
		#eneble user
		if (isset( $requestParams [UsersService::ENABLED] )) {
			$where = UsersService::ROLE . " = '" . UsersService::ROLE_UR . "'";
			$userList = UsersService::getUsersList($where);
			foreach($userList as $key => $value){
				if ($value[UsersService::ID] == $requestParams[UsersService::ENABLED])
				UsersService::updateFields ( $requestParams [UsersService::ENABLED], UsersService::ENABLED, '1' );
			}
		}
		#disable user
		if (isset( $requestParams [UsersService::DISABLE] )) {
			$where = UsersService::ROLE . " = '" . UsersService::ROLE_UR . "'";
			$userList = UsersService::getUsersList($where);
			foreach($userList as $key => $value){
				if ($value[UsersService::ID] == $requestParams[UsersService::DISABLE])
					UsersService::updateFields ( $requestParams [UsersService::DISABLE], UsersService::ENABLED, '0' );
			}
		}
		#remove user
		if (isset( $requestParams [UsersService::DELETED] )) {
			$where = UsersService::ROLE . " = '" . UsersService::ROLE_UR . "'";
			$userList = UsersService::getUsersList($where);
			foreach($userList as $key => $value){
				if ($value[UsersService::ID] == $requestParams[UsersService::DELETED])
					UsersService::updateFields ( $requestParams [UsersService::DELETED], UsersService::DELETED, '1' );
				}
		}
		
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		#get school id for this moderator 
		$where = SchoolService::ADMIN . '=' . $id;
		$result = SchoolService::getSchoolsList( $where );
		#creating SQL WHERE for below query 
		$where = NULL;
		if( count($result) >0 ){
			$where .= AssignmentsService::SCHOOLS_ASSIGNED . '.' .
				AssignmentsService::SCHOOL_ID . SQLClient::IN . "(";
			foreach ($result as $key => $value){  
				$where .= "'" . $value[SchoolService::ID] . "'";
				$where .= $key+1 < count($result) ? ", " : NULL;
				
			}
			$where .= ") AND " ;
		}
		#creating SQL WHERE for geting user from scroller
		$where .= isset ( $requestParams [UsersService::SCROLLER]) ? 
				"UCASE( LEFT (lastname, 1 ) )" . "= '" . $requestParams [UsersService::SCROLLER] . "' AND " : NULL;
		
		#get user list from school where this user is moderator
		$where .= UsersService::DELETED . " = 0 AND " . UsersService::USERS . '.' . 
				UsersService::ID . "!=" . $id . " AND " . 
				UsersService::ROLE . SQLClient::NOT . SQLClient::IN . "('" . 
				UsersService::ROLE_AR . "', '" . UsersService::ROLE_MR . "')";
		
		$from = UsersService::USERS . SQLClient::JOIN . AssignmentsService::SCHOOLS_ASSIGNED . SQLClient::ON . UsersService::USERS . '.' . UsersService::ID . '=' . AssignmentsService::SCHOOLS_ASSIGNED . '.' . AssignmentsService::USER_ID;
		$result = UsersService::getUsersList ( $where, $from );
		$mvc->addObject ( self::RESULT, $result );
		#get scroller for displayed users
		$NoRole = "'" . UsersService::ROLE_AR . "', '" . UsersService::ROLE_MR . "'";
		$scroller = $result != NULL ? UsersService::chrScroller(UsersService::LASTNAME, NULL, $NoRole) : NULL ;
		$mvc->addObject ( 'scroller', $scroller);
		
		return $mvc;
	}
	
	public function handleEditUser($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		#for admin and moderator
		if (isset($requestParams[UsersService::ID])){
			$where = UsersService::ROLE . " = '" . UsersService::ROLE_UR . "'";
			$userList = UsersService::getUsersList($where);
			foreach($userList as $key => $value){
				if ($value[UsersService::ID] == $requestParams[UsersService::ID]){	
					$where = UsersService::ID . " = '" . $requestParams [UsersService::ID] . "'";
					$result = UsersService::getUsersList ( $where );
					$mvc->addObject ( self::RESULT, $result[0] );
				}
			}
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
				$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::FIRSTNAME] . "','" . $requestParams [UsersService::LASTNAME] . "','" . $requestParams [UsersService::EMAIL] . "','','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "','1','" . UsersService::ROLE_UR . "','" . $path . "'";
				$into = UsersService::USERS;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				
				$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [UsersService::EMAIL] . '&validation_id=' . $hash;
				$plain = $mvc->getProperty ( 'template' );
				
				MailerService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $plain, $url);
				$mvc->addObject ( 'forward', 'successful' );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		return $mvc;
	}
	
	public function handleManageCategories($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#edit category
		if (isset ( $requestParams ['submit'] )) {
			$fields = array ();
			$fields [] .= CategoriesService::NAME;
			$vals = array ();
			$id = $requestParams [CategoriesService::ID];
			$vals [] .= $requestParams [CategoriesService::NAME];
			CategoriesService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( 'forward', 'successful' );
		}
		
		#remove category
		isset ( $requestParams [UsersService::DELETED] ) ? CategoriesService::deleteCategories ( $requestParams [UsersService::DELETED]) : '';
		
		$mrSchList = SchoolService::getMrSchools();
		$mvc->addObject ( 'mrSchList' , $mrSchList );
		
		#get user list from school where this user is moderator
		$result = CategoriesService::getCategoriesList ();
		$mvc->addObject ( self::RESULT, $result );
		return $mvc;
	}
	
	public function handleNewCategory($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$mrSchList = SchoolService::getMrSchools();
		$mvc->addObject ( 'mrSchList' , $mrSchList );
		
		if (isset ( $requestParams ['submit'] )) {
			if ($requestParams[CategoriesService::NAME] != NULL) {
				// Insert new categories to DB
				$fields = CategoriesService::NAME . ", " . CategoriesService::SCHOOL_ID;
				$values = "'" . $requestParams[CategoriesService::NAME] . "', '" . $requestParams[CategoriesService::SCHOOL_ID] . "'";
				$into = CategoriesService::CATEGORIES_TABLE;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				$mvc->addObject ( 'forward', 'successful' );
			} else {
				$mvc->addObject ( UsersService::ERROR, 'error' );
			}
		}
		return $mvc;
	}
	public function handleEditCategory($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset($requestParams[CategoriesService::ID])){
			$ctgList = CategoriesService::getCategoriesList();
			foreach($ctgList as $key => $value){
				if ($value[CategoriesService::ID] == $requestParams[CategoriesService::ID]){	
					$where = CategoriesService::CATEGORIES_TABLE . '.' . CategoriesService::ID . " = '" . $requestParams [CategoriesService::ID] . "'";
					$result = CategoriesService::getCategoriesList ( $where );
					$mvc->addObject ( self::RESULT, $result [0] );
				}
			}
		}	
		return $mvc;
	}
	
	
	/*private function hasSchlAssign(){
		$result = null;
		# preparing query
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = SchoolService::ID;
		$from = SchoolService::SCHOOLS_TABLE;
		$where = SchoolService::ADMIN . " = '" . $id . "'";
		# executing query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : null;
		return $result;
	}*/
}

?>