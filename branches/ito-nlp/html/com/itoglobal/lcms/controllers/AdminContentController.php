<?php

require_once 'com/itoglobal/lcms/controllers/ContentController.php';

class AdminContentController extends ContentController {
	
	/**
	 * @var string defines the user details constant
	 */
	const USER_DETAILS = 'USER';
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		#for admin
		isset ( $requestParams [UsersService::ENABLED] ) ? SchoolService::updateFields ( $requestParams [SchoolService::ENABLED], SchoolService::ENABLED, '1' ) : '';
		isset ( $requestParams [UsersService::DISABLE] ) ? SchoolService::updateFields ( $requestParams [SchoolService::DISABLE], SchoolService::ENABLED, '0' ) : '';
		isset ( $requestParams [SchoolService::DELETED] ) ? SchoolService::deleteSchool ( $requestParams [SchoolService::DELETED] ) : null;
		$list = SchoolService::getSchoolsList ();
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

				/*$fields = UsersService::ID;
				$from = UsersService::USERS;
				$where = UsersService::USERNAME . "='" . $requestParams [SchoolService::ADMIN] . "'";
				$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
				$admin = $result [0] [UsersService::ID];*/
				
				// Insert new school to DB
				$fields = SchoolService::ALIAS . ', ' . SchoolService::CAPTION . ', ' . SchoolService::DESCRIPTION . ', ' . SchoolService::AVATAR . ', ' . SchoolService::CRDATE . ', ' . SchoolService::FEE . ', ' . SchoolService::ADMIN . ', ' . SchoolService::LANGUAGE;
				$owner_id = SessionService::getAttribute ( SessionService::USERS_ID );
				$values = "'" . $requestParams [SchoolService::ALIAS] . "','" . $requestParams [SchoolService::CAPTION] . "','" . $requestParams [SchoolService::DESCRIPTION] . "','" . $path . "','" . gmdate ( "Y-m-d H:i:s" ) . "','0','" . $requestParams[SchoolService::ADMIN] . "','" . $requestParams[SchoolService::LANGUAGE] ."'";
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
			$mvc->addObject ( 'list', $list );
		}	
		return $mvc;
	}

	public function handleEditSchool($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[SchoolService::ID])){
		
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
				$fields [] .= SchoolService::ADMIN;
				
				$vals = array ();
				$id = $requestParams [SchoolService::ID];
				$vals [] .= $requestParams [SchoolService::CAPTION];
				$vals [] .= $requestParams [SchoolService::DESCRIPTION];
				$vals [] .= $requestParams [SchoolService::ADMIN];			
				
				SchoolService::updateFields ( $id, $fields, $vals );
			}
			
			$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
			$result = SchoolService::getSchoolsList ( $where );
			isset ( $result [0] [SchoolService::ID] ) ? $mvc->addObject ( SchoolService::ID, $result [0] [SchoolService::ID] ) : null;
			isset ( $result [0] [SchoolService::CAPTION] ) ? $mvc->addObject ( SchoolService::CAPTION, $result [0] [SchoolService::CAPTION] ) : null;
			isset ( $result [0] [SchoolService::DESCRIPTION] ) ? $mvc->addObject ( SchoolService::DESCRIPTION, $result [0] [SchoolService::DESCRIPTION] ) : null;
			isset ( $result [0] [SchoolService::AVATAR] ) ? $mvc->addObject ( SchoolService::AVATAR, $result [0] [SchoolService::AVATAR] ) : null;
			isset ( $result [0] [SchoolService::ALIAS] ) ? $mvc->addObject ( SchoolService::ALIAS, $result [0] [SchoolService::ALIAS] ) : null;
			isset ( $result [0] [UsersService::USERNAME] ) ? $mvc->addObject ( UsersService::USERNAME, $result [0] [UsersService::USERNAME] ) : null;
			isset ( $result [0] [SchoolService::ADMIN] ) ? $mvc->addObject ( SchoolService::ADMIN, $result [0] [SchoolService::ADMIN] ) : null;
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
		isset ( $id ) ? $where .= " and " . UsersService::ID . "!=" . $id : '';
		$result = UsersService::getUsersList ( $where );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		
		return $mvc;
	}
	
	public function handleEditUser($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[UsersService::ID])){
			#for admin and moderator
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
	
}

?>