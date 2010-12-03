<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class AdminContentController extends ContentController {
	
	
	public function handleManageCategory($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$categories = CategoryService::getCategories();
		isset($categories) ? $mvc->addObject ( CategoryService::CATEGORY, $categories) : NULL;
		
		$subcategories = SubCategoryService::getSubCategories();
		isset($subcategories) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategories) : NULL;
		
		return $mvc;
	}
	
	public function handleManageUsers($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		#update users info
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
			$mvc->addObject ( 'forward', 'successful' );
		}
		isset ( $requestParams [UsersService::ENABLED] ) ? UsersService::updateFields ( $requestParams [UsersService::ENABLED], UsersService::ENABLED, '1' ) : '';
		isset ( $requestParams [UsersService::DISABLE] ) ? UsersService::updateFields ( $requestParams [UsersService::DISABLE], UsersService::ENABLED, '0' ) : '';
		isset ( $requestParams [UsersService::DELETED] ) ? UsersService::deleteUser ( $requestParams [UsersService::DELETED]) : '';
		#isset ( $requestParams [UsersService::DELETED] ) ? UsersService::updateFields ( $requestParams [UsersService::DELETED], UsersService::DELETED, '1' ) : '';
		
		
		
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
			$result = isset($result [0]) ? $result[0] : null;
			$mvc->addObject ( self::RESULT, $result );
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
				StorageService::createDirectory ( StorageService::USERS_FOLDER . $requestParams [UsersService::USERNAME] );
				StorageService::createDirectory ( StorageService::USERS_FOLDER . $requestParams [UsersService::USERNAME] . StorageService::USER_PROFILE );
				$path = StorageService::USERS_FOLDER . $requestParams [UsersService::USERNAME] . StorageService::USER_PROFILE . StorageService::USER_AVATAR;
				copy ( StorageService::DEF_USER_AVATAR, $path );
				
				$fields = UsersService::USERNAME . ', ' . UsersService::FIRSTNAME . ', ' . UsersService::LASTNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . UsersService::CRDATE . ', ' . UsersService::VALIDATION . ', ' . UsersService::ENABLED . ', ' . UsersService::ROLE . ', ' . UsersService::AVATAR;
				$hash = md5 ( rand ( 1, 9999 ) );
				$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::FIRSTNAME] . "','" . $requestParams [UsersService::LASTNAME] . "','" . $requestParams [UsersService::EMAIL] . "','','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "','1','" . $requestParams [UsersService::ROLE] . "','" . $path . "'";
				$into = UsersService::USERS;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				#get user id 
				$id = $result;
				$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?id=' . $id . '&validation_id=' . $hash;
				$plain = $mvc->getProperty ( 'template' );
				
				MailerService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $plain, $url);
				$mvc->addObject ( 'forward', 'successful' );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
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

