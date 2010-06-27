<?php

require_once 'com/itoglobal/lcms/controllers/ContentController.php';

class AdminContentController extends ContentController {
	
	/**
	 * @var string defines the user details constant
	 */
	const USER_DETAILS = 'USER';
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#schools options
		isset ( $requestParams [UsersService::ENABLED] ) ? SchoolService::updateFields ( $requestParams [SchoolService::ENABLED], SchoolService::ENABLED, '1' ) : '';
		isset ( $requestParams [UsersService::DISABLE] ) ? SchoolService::updateFields ( $requestParams [SchoolService::DISABLE], SchoolService::ENABLED, '0' ) : '';
		isset ( $requestParams [SchoolService::DELETED] ) ? SchoolService::deleteSchool ( $requestParams [SchoolService::DELETED] ) : null;
		
		#schools sorting
		$orderBy = isset ( $requestParams ['popular'] ) ? SchoolService::RATE . ' ' . SQLClient::DESC : NULL;
		$orderBy = isset ( $requestParams ['recent'] ) ? SchoolService::CRDATE . ' ' . SQLClient::DESC : $orderBy;
		
		#get schools list
		$list = SchoolService::getSchoolsList ( null, null, $orderBy);
		$mvc->addObject ( 'list', $list );
		
		return $mvc;
	}
	
	public function handleNewSchool($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#moderator list for admin
		$where = UsersService::ROLE . "= '" . UsersService::ROLE_MR . "'";
		$mrList = UsersService::getUsersList($where);
		$mvc->addObject ( 'mrList', $mrList );
		
		if (isset ( $requestParams ['submit'] )) {
			//server-side validation
			$error = SchoolService::validation ( $requestParams,  $_FILES );
			if (isset ( $error ) && count ( $error ) == 0) {
				StorageService::createDirectory ( 'storage/uploads/schools/' . $requestParams [SchoolService::ALIAS] );
				$path = 'storage/uploads/schools/' . $requestParams [SchoolService::ALIAS] . "/avatar.jpg";
				
				isset ( $_FILES ['file'] ) && $_FILES ['file'] ['error'] == 0 ?
					StorageService::uploadFile ( $path, $_FILES ['file'] ) :
						copy ( 'storage/uploads/default-school.jpg', $path );

				// Insert new school to DB
				$fields = SchoolService::ALIAS . ', ' . SchoolService::CAPTION . ', ' . 
						SchoolService::DESCRIPTION . ', ' . SchoolService::AVATAR . ', ' . 
						SchoolService::CRDATE . ', ' . SchoolService::BASE_FEE . ', ' . 
						SchoolService::ADMIN . ', ' . SchoolService::LANGUAGE;
				$owner_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$values = "'" . $requestParams [SchoolService::ALIAS] . "','" . 
						$requestParams [SchoolService::CAPTION] . "','" . 
						$requestParams [SchoolService::DESCRIPTION] . "','" . $path . "','" . 
						gmdate ( "Y-m-d H:i:s" ) . "','0','" . $requestParams[SchoolService::ADMIN] . "','" . 
						$requestParams[SchoolService::LANGUAGE] ."'";
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
	
	public function handleSchoolDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[SchoolService::ID])){
			#for all
			$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
			$list = SchoolService::getSchoolsList ( $where );
			$mvc->addObject ( 'list', $list[0] );
		}	
		return $mvc;
	}

	public function handleEditSchool($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[SchoolService::ID])){
		
			#moderator list for admin
			$where = UsersService::ROLE . " != '" . UsersService::ROLE_AR . "'" ;
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
				$fields [] .= SchoolService::ADMIN;
				
				$vals = array ();
				$id = $requestParams [SchoolService::ID];
				$vals [] .= $requestParams [SchoolService::CAPTION];
				$vals [] .= $requestParams [SchoolService::DESCRIPTION];
				$vals [] .= $requestParams [SchoolService::ADMIN];			
				
				SchoolService::updateFields ( $id, $fields, $vals );
				
				#change moderator
				$requestParams[SchoolService::OLD_ADMIN] != $requestParams[SchoolService::ADMIN] ?
					self::changeMR($requestParams [SchoolService::ADMIN], $requestParams [SchoolService::OLD_ADMIN]) : 
						NULL;
				
			}
			
			$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
			$result = SchoolService::getSchoolsList ( $where );
			$mvc->addObject ( self::RESULT, $result [0] );
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
		
		$where = NULL;
		#user sorting
		$where .= isset ( $requestParams [UsersService::ROLE]) ? 
				UsersService::ROLE . "= '" . $requestParams [UsersService::ROLE] . "'" : NULL;
		$where .= $where == NULL ? NULL : ' AND ';
		$where .= isset ( $requestParams [UsersService::SCROLLER]) ? 
				"UCASE( LEFT (lastname, 1 ) )" . "= '" . $requestParams [UsersService::SCROLLER] . "' AND " : NULL;
		
		$where .= UsersService::DELETED . " = 0";// AND " . UsersService::ROLE . "!='" . UsersService::ROLE_UR . "'";
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$where .= " and " . UsersService::ID . "!=" . $id;
		$result = UsersService::getUsersList ( $where );
		$mvc->addObject ( self::RESULT, $result);
		$role = isset($requestParams [UsersService::ROLE]) ? $requestParams [UsersService::ROLE] : NULL;
		$scroller = $result != NULL ? UsersService::chrScroller(UsersService::LASTNAME, $role) : NULL ;
		$mvc->addObject ( 'scroller', $scroller);
		return $mvc;
	}
	
	public function handleEditUser($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[UsersService::ID])){
			#for admin and moderator
			$where = UsersService::ID . " = '" . $requestParams [UsersService::ID] . "'";
			$result = UsersService::getUsersList ( $where );
			$mvc->addObject ( self::RESULT, $result[0] );
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
	
	/**
	 * Handles the user details page request. 
	 * @param mixed $actionParams
	 * @param mixed $requestParams
	 * @return ModelAndView
	 */
	public function handleUserDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user = UsersService::getUser($requestParams[UsersService::ID]);
		$mvc->addObject(self::USER_DETAILS, $user);		
		return $mvc;	
	}
	
	private static function changeMR($id, $old_admin){
		#new moderator
		$fields [] .= UsersService::ROLE;
		$vals[] .= UsersService::ROLE_MR;
		UsersService::updateFields ( $id, $fields, $vals );
		
		#old moderator
		#if old moderator had access to moderate some schools he should be a moderator, else - user
		$where = SchoolService::ADMIN . "='" . $old_admin . "'";
		$result = SchoolService::getSchoolsList($where);
		if ($result == null) {
			$fields [] .= UsersService::ROLE;
			$vals[] .= UsersService::ROLE_UR;
			UsersService::updateFields ( $old_admin, $fields, $vals );
		}
	}
}

?>