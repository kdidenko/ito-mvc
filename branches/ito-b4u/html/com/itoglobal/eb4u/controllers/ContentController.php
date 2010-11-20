<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	const RESULT = 'result';
	/**
	 * @var string defines the user details constant
	 */
	const USER_DETAILS = 'USER';
	
	public function handleHome($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleForEntrepreneurs($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleRegistration($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleConfirmRegistration($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleResetPassword($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleCheckEmail($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleValidation($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleNewPassword($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleHelp($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleLoginFailed($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleLogin($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleSignIn($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleSchools($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#sorting
		$order = NULL;
		if( isset($requestParams[SchoolService::RATE]) ){
			$order = $requestParams[SchoolService::RATE] == SQLClient::ASC ? 
				SchoolService::RATE . ' ' . SQLClient::ASC : 
					SchoolService::RATE . ' ' . SQLClient::DESC;
		}
		if( isset($requestParams[SchoolService::LANGUAGE]) ){
			$order = $requestParams[SchoolService::LANGUAGE] == SQLClient::ASC ? 
				SchoolService::LANGUAGE . ' ' . SQLClient::ASC : 
					SchoolService::LANGUAGE . ' ' . SQLClient::DESC;
		}
		
		#get schools list
		$list = SchoolService::getSchoolsList (null, null, $order);
		$list = self::createTeaser($list);
		$mvc->addObject ( 'list', $list );
		return $mvc;
	}
	public function handleSchoolDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		# getting the school
		$school = SchoolService::getSchoolsList(NULL, NULL, NULL, $requestParams [SchoolService::ID]);
		$school = $school[0];
		$mvc->addObject ('list', $school);
		# getting list of courses
		$where = CourseService::COURSE_TABLE . '.' . CourseService::SCHOOL_ID . " = '" . $requestParams [CourseService::ID] . "'";
		$courselist = CourseService::getCoursesList ($where);
		$courselist = self::createTeaser($courselist);
		$mvc->addObject ('courselist', $courselist);
		return $mvc;
	}
	public function handleCourses($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#sorting
		$order = NULL;
		if( isset($requestParams[CourseService::RATE]) ){
			$order = $requestParams[CourseService::RATE] == SQLClient::ASC ? CourseService::RATE . ' ' . SQLClient::ASC : CourseService::RATE . ' ' . SQLClient::DESC;
		}
		
		#get course list
		$list = CourseService::getCoursesList (null, null, $order);
		$list = self::createTeaser($list);
		$mvc->addObject ( 'list', $list );
		
		return $mvc;
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
	public function handleCourseDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[CourseService::ID])){
			#for users and visitor
			$where = CourseService::COURSE_TABLE . '.' . CourseService::ID . " = '" . $requestParams [CourseService::ID] . "'";
			$list = CourseService::getCoursesList ( $where );
			$mvc->addObject ( 'list', $list [0]);
			#for users and visitor
			$where = ExerciseService::COURSE_ID . " = '" . $requestParams [ExerciseService::ID] . "'";
			$exerciselist = ExerciseService::getExercisesList( $where );
			$exerciselist = self::createTeaser($exerciselist);
			$mvc->addObject ( 'exerciselist', $exerciselist);
		}
		return $mvc;
	}
		
	public function handleBrowseExercises($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		#for visitor and user
		$list = ExerciseService::getExercisesList ();
		$mvc->addObject ( 'list', $list );
		return $mvc;
	}
	
	public function handleExerciseDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute(SessionService::USERS_ID);
		$where = ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::ID . "='" . 
					$requestParams [ExerciseService::ID] . "'";
		$result = ExerciseService::getAccessEx($user_id, $where);
		if (count($result)){
			#for moderator, user, visitor
			$where = ExerciseService::ID . " = '" . $requestParams [ExerciseService::ID] . "'";
			$list = ExerciseService::getExercisesList ( $where );
			$mvc->addObject ( 'list', $list [0]);
		}			
		return $mvc;
	}
	public function handleMyProfile($actionParams, $requestParams) {
		#for all
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$username = SessionService::getAttribute ( UsersService::USERNAME );
		$error = array ();
		
		if (isset ( $requestParams ['pctSbm'] )) {
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$file = $_FILES ['file'];
				$path = 'storage/uploads/users/' . $username . "/profile/avatar.jpg";
				$error[] .= ValidationService::checkAvatar ( $file );
				$error = array_filter ( $error );
			}
			if (count ( $error ) == 0) {
				if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
					StorageService::uploadFile ( $path, $file );
					$mvc->addObject ( 'forward', 'successful' );
					self::setNoCashe();
				}
			}else{
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		
		if (isset ( $requestParams ['pswSbm'] )) {
			$error [] .= isset($requestParams [UsersService::OLDPASSWORD]) ? UsersService::checkOldPassword ( $requestParams [UsersService::OLDPASSWORD] ) : false;
			$error [] .= isset($requestParams [UsersService::PASSWORD]) ? UsersService::checkPassword ( $requestParams [UsersService::PASSWORD] ) : false;
			$error [] .= isset($requestParams [UsersService::CONFIRM]) ? UsersService::checkConfirmPassword ( $requestParams [UsersService::PASSWORD], $requestParams [UsersService::CONFIRM] ) : false;
			$error = array_filter ( $error );
			
			if (count ( $error ) == 0) {
				if (isset($requestParams[UsersService::OLDPASSWORD]) && $requestParams[UsersService::OLDPASSWORD] != null){
					$fields = UsersService::PASSWORD;
					$from = UsersService::USERS;
					$where = UsersService::ID . " = " . $requestParams[UsersService::ID];
					# executing the query
					$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
					if (md5($requestParams[UsersService::OLDPASSWORD]) == $result[0][UsersService::PASSWORD]){
						$fields = array ();
						$fields[] .= UsersService::PASSWORD;
						$vals = array ();
						$vals [] .= md5($requestParams [UsersService::PASSWORD]);
						UsersService::updateFields ( $id, $fields, $vals );
						$mvc->addObject ( 'forward', 'successful' );
					} else {
						$error [] .= 'You enter wrong old password. Please try again.';
						$mvc->addObject ( UsersService::ERROR, $error );
					}
				}
			}else{
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		
		if (isset ( $requestParams ['personalSbm'] )) {		
			$fields = array ();
			$fields [] .= UsersService::FIRSTNAME;
			$fields [] .= UsersService::LASTNAME;
			$fields [] .= UsersService::EMAIL;
			$fields [] .= UsersService::BIRTHDAY;
			$fields [] .= UsersService::SKYPE;
			$fields [] .= UsersService::GENDER;
			$vals = array ();
			$vals [] .= $requestParams [UsersService::FIRSTNAME];
			$vals [] .= $requestParams [UsersService::LASTNAME];
			$vals [] .= $requestParams [UsersService::EMAIL];
			$vals [] .= $requestParams [UsersService::BIRTHDAY];
			$vals [] .= $requestParams [UsersService::SKYPE];
			$vals [] .= $requestParams [UsersService::GENDER];
			
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( 'forward', 'successful' );
		}
		
		$where = UsersService::ID . " = '" . $id . "'";
		$result = UsersService::getUsersList ( $where );
		if(isset ( $result [0] )){
			$mvc->addObject ( UsersService::ID, $result [0] [UsersService::ID] );
			$mvc->addObject ( UsersService::AVATAR, $result [0] [UsersService::AVATAR] );
			$mvc->addObject ( UsersService::LASTNAME, $result [0] [UsersService::LASTNAME] );
			$mvc->addObject ( UsersService::FIRSTNAME, $result [0] [UsersService::FIRSTNAME] );
			$mvc->addObject ( UsersService::EMAIL, $result [0] [UsersService::EMAIL] );
			$mvc->addObject ( UsersService::BIRTHDAY, $result [0] [UsersService::BIRTHDAY] );
			$mvc->addObject ( UsersService::SKYPE, $result [0] [UsersService::SKYPE] );
			$mvc->addObject ( UsersService::GENDER, $result [0] [UsersService::GENDER] );
		}
		return $mvc;
	}
	
	/**
	 * Handles the user details page request. 
	 * @param mixed $actionParams
	 * @param mixed $requestParams
	 * @return ModelAndView
	 */
	public function handleUserDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[CategoriesService::ID])){
			$user = UsersService::getUser($requestParams[UsersService::ID]);
			
			list($year,$month,$day) = explode("-",$user[UsersService::BIRTHDAY]);
		    $year_diff  = date("Y") - $year;
		    $month_diff = date("m") - $month;
		    $day_diff   = date("d") - $day;
		    if ($day_diff < 0 || $month_diff < 0)
		      $year_diff--;
			$age = $year_diff;
			$user['age'] = $age; 
			$mvc->addObject(self::USER_DETAILS, $user);	
		}
		return $mvc;	
	}
	
	public static function createTeaser ($list){
		if (count($list)>0){
			foreach($list as $key => $value){
				$chr = strpos($value[SchoolService::DESCRIPTION], '</p>');
				$value[SchoolService::DESCRIPTION] = $chr != NULL ? 
					substr($value[SchoolService::DESCRIPTION],0,$chr) : 
						$value[SchoolService::DESCRIPTION];
				$value[SchoolService::DESCRIPTION] = substr($value[SchoolService::DESCRIPTION], 0, 255);
				$list[$key][SchoolService::DESCRIPTION] = strrev(strstr(strrev($value[SchoolService::DESCRIPTION]), ' '));
			}
		}
		return $list;
	}
	public static function createTeaserWord ($word){
		$word = substr($word, 0, 255);
		$word = strrev(strstr(strrev($word), ' '));
		return $word;
	}
}
?>