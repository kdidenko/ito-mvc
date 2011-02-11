<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class AdminContentController extends ContentController {
	
	public function handleMyProfile($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$location = $this->onSuccess( $actionParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$error = array ();
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
						$mvc->addObject ( self::STATUS, 'successful' );
					} else {
						$error [] .= '_i18n{You enter wrong old password. Please try again.}';
						$mvc->addObject ( self::PSW_ERROR, $error );
					}
				}
			}else{
				$mvc->addObject ( self::PSW_ERROR, $error );
			}
		}
		
		if (isset ( $requestParams ['personalSbm'] )) {		
			$fields = array (
							'0'=>UsersService::FIRSTNAME, 
							'1'=>UsersService::LASTNAME,
							'2'=>UsersService::EMAIL,
							'5'=>UsersService::ADDRESS,
							'6'=>UsersService::ZIP,
							'7'=>UsersService::LOCATION,
							'8'=>UsersService::REGION,
							'9'=>UsersService::COUNTRY,
							'10'=>UsersService::PHONE,
							'11'=>UsersService::SALUTATION
							);
			$vals = array (
							'0'=>$requestParams [UsersService::FIRSTNAME], 
							'1'=>$requestParams [UsersService::LASTNAME],
							'2'=>$requestParams [UsersService::EMAIL],
							'5'=>$requestParams [UsersService::ADDRESS],
							'6'=>$requestParams [UsersService::ZIP],
							'7'=>$requestParams [UsersService::LOCATION],
							'8'=>$requestParams [UsersService::REGION],
							'9'=>$requestParams [UsersService::COUNTRY],
							'10'=>$requestParams [UsersService::PHONE],
							'11'=>$requestParams [UsersService::SALUTATION]
							);
			
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( self::STATUS, 'successful' );
		}
		
		#get user info
		$result = UsersService::getUser ( $id );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;

		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
		return $mvc;
	}
	
	public function handleManageStaticBlock($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			StaticBlockService::deleteBlocks ($requestParams ['itemSelect']) :
				null;
		isset ( $requestParams [self::DEL] ) ? StaticBlockService::deleteBlock ($requestParams [self::DEL]) : null;
		
		$blocks = StaticBlockService::getBlocks();
		isset($blocks) ? $mvc->addObject ( StaticBlockService::STATIC_BLOCK, $blocks) : NULL;
		
		return $mvc;
	}
	
	public function handleNewBlock($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$location = $this->onSuccess( $actionParams );
		
		isset($requestParams['back']) ? $this->forwardActionRequest ( $location ) : NULL;
		
		if(isset($requestParams[StaticBlockService::SAVE])){
			StaticBlockService::createBlock($requestParams[StaticBlockService::BLOCK_TITLE],$requestParams[StaticBlockService::BLOCK_DESC],$requestParams[StaticBlockService::BLOCK_PAGE]);
			$this->forwardActionRequest ( $location );
		}
		return $mvc;
	}
	
	public function handleEditBlock($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$location = $this->onSuccess( $actionParams );
		
		isset($requestParams['back']) ? $this->forwardActionRequest ( $location ) : NULL;
		if(isset($requestParams[StaticBlockService::ID])){
			$id = $requestParams[StaticBlockService::ID];
			if(isset($requestParams[StaticBlockService::SAVE_CONTINUE]) || isset($requestParams[StaticBlockService::SAVE])){
				$fields = array();
				$vals = array();
				$fields[] .= StaticBlockService::BLOCK_TITLE;
				$fields[] .= StaticBlockService::BLOCK_DESC;
				$fields[] .= StaticBlockService::BLOCK_PAGE;
				$vals[] .= htmlspecialchars($requestParams[StaticBlockService::BLOCK_TITLE], ENT_QUOTES);
				$vals[] .= htmlspecialchars($requestParams[StaticBlockService::BLOCK_DESC], ENT_QUOTES);
				$vals[] .= htmlspecialchars($requestParams[StaticBlockService::BLOCK_PAGE], ENT_QUOTES);
				StaticBlockService::updateBlock($id, $fields, $vals);
				isset($requestParams[StaticBlockService::SAVE]) ? $this->forwardActionRequest ( $location ) : NULL;
				$mvc->addObject ( self::STATUS, 'successful' );
			}
			
			$block = StaticBlockService::getBlockInfo($id);
			isset($block) ? $mvc->addObject ( StaticBlockService::STATIC_BLOCK, $block) : NULL;
		}
				
		
		return $mvc;
	}
	
	public function handleManageCategory($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if(isset($requestParams['delCategory'])){  
			print_r($requestParams['itemSelectCategory']);
			print_r($requestParams['itemSelectSubCategory']);
			/*
			if (isset($requestParams['itemSelectCategory']) && strlen($requestParams['itemSelectCategory'])>0){
				$array = explode(',', $requestParams["itemSelectCategory"]);
				foreach ($array as $id){
					CategoryService::deleteCategory($id);
				}
			}
			if (isset($requestParams['itemSelectSubCategory']) && strlen($requestParams['itemSelectSubCategory'])>0){
				$array = explode(',', $requestParams["itemSelectSubCategory"]);
				foreach ($array as $id){
					SubCategoryService::deleteSubCategory($id);
				}
			}
			*/
		}
				
		$categories = CategoryService::getCategories();
		isset($categories) ? $mvc->addObject ( CategoryService::CATEGORY, $categories) : NULL;
		
		$subcategories = SubCategoryService::getSubCategories();
		isset($subcategories) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategories) : NULL;

		return $mvc;
	}
	
	public function handleNewCategory($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset($_POST)&&$_POST!=NULL){
			$length = strlen ($requestParams[CategoryService::CAT_NAME])  -   substr_count($requestParams[CategoryService::CAT_NAME], ' '); 
			if (isset($requestParams[CategoryService::CAT_NAME])&&$requestParams[CategoryService::CAT_NAME]!=NULL&&$length>0) {
				$where = CategoryService::CAT_NAME . "='" . $requestParams[CategoryService::CAT_NAME] . "'";
				$catgories = CategoryService::getCategories($where);
				if (isset($catgories)&&$catgories==NULL){
					CategoryService::createNewCat($requestParams[CategoryService::CAT_NAME]);
					$mvc->addObject ( ContentController::STATUS, true );
				} else {
					$mvc->addObject ( ContentController::ERROR, "_i18n{Such category already exist.}" );
				}
			} else {
				$mvc->addObject ( ContentController::ERROR, "_i18n{Category name can't be empty.}" );
			}
		}

		return $mvc;
	}
	
	public function handleEditCategory($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if(isset($requestParams[CategoryService::ID])){
			$id = $requestParams[CategoryService::ID];
			if (isset($_POST)&&$_POST!=NULL){
				$length = strlen ($requestParams[CategoryService::CAT_NAME])  -   substr_count($requestParams[CategoryService::CAT_NAME], ' '); 
				if (isset($requestParams[CategoryService::CAT_NAME])&&$requestParams[CategoryService::CAT_NAME]!=NULL&&$length>0) {
					CategoryService::updateCategory($id,CategoryService::CAT_NAME,"'".$requestParams[CategoryService::CAT_NAME]."'");
					$mvc->addObject ( ContentController::STATUS, true );
				} else {
					$mvc->addObject ( ContentController::ERROR, "_i18n{Category name can't be empty.}" );
				}
			}
			$category = CategoryService::getCategory($id);
			isset($category) ? $mvc->addObject ( CategoryService::CATEGORY, $category) : NULL;
		}
		return $mvc;
	}
	
	public function handleNewSubCategory($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset($_POST)&&$_POST!=NULL){
			$length = strlen ($requestParams[SubCategoryService::SUBCAT_NAME])  -   substr_count($requestParams[SubCategoryService::SUBCAT_NAME], ' '); 
			if (isset($requestParams[SubCategoryService::SUBCAT_NAME])&&$requestParams[SubCategoryService::SUBCAT_NAME]!=NULL&&$length>0) {
				$where = SubCategoryService::SUBCAT_NAME . "='" . $requestParams[SubCategoryService::SUBCAT_NAME] . "'";
				$subcatgories = SubCategoryService::getSubCategories($where);
				if (isset($subcatgories)&&$subcatgories==NULL){
					SubCategoryService::createNewSubCat($requestParams[SubCategoryService::SUBCAT_NAME],$requestParams[SubCategoryService::CAT_ID]);
					$mvc->addObject ( ContentController::STATUS, true );
				} else {
					$mvc->addObject ( ContentController::ERROR, "_i18n{Such SubCategory already exist in} <b>" . $subcatgories[0][CategoryService::CAT_NAME] . "</b> _i18n{Category}." );
				}
			} else {
				$mvc->addObject ( ContentController::ERROR, "_i18n{SubCategory name can't be empty.}" );
			}
		}
		
		$categories = CategoryService::getCategories();
		isset($categories) ? $mvc->addObject ( CategoryService::CATEGORY, $categories) : NULL;
		
		return $mvc;
	}
	
	public function handleEditSubCategory($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if(isset($requestParams[SubCategoryService::ID])){
			$id = $requestParams[SubCategoryService::ID];
			if (isset($_POST)&&$_POST!=NULL){
				$length = strlen ($requestParams[SubCategoryService::SUBCAT_NAME])  -   substr_count($requestParams[SubCategoryService::SUBCAT_NAME], ' '); 
				if (isset($requestParams[SubCategoryService::SUBCAT_NAME])&&$requestParams[SubCategoryService::SUBCAT_NAME]!=NULL&&$length>0) {
					$where = SubCategoryService::SUBCAT_NAME . "='" . $requestParams[SubCategoryService::SUBCAT_NAME] . "'";
					$subcatgories = SubCategoryService::getSubCategories($where);
					if (isset($subcatgories)&&$subcatgories==NULL){
						$fields = array(0=>SubCategoryService::SUBCAT_NAME,
										1=>SubCategoryService::CAT_ID);
						$value = array(0=>$requestParams[SubCategoryService::SUBCAT_NAME],
										1=>$requestParams[SubCategoryService::CAT_ID]);
						SubCategoryService::updateSubCategory($id,$fields,$value);
						$mvc->addObject ( ContentController::STATUS, true );
					} else {
						$mvc->addObject ( ContentController::ERROR, "_i18n{Such SubCategory already exist in} <b>" . $subcatgories[0][CategoryService::CAT_NAME] . "</b> _i18n{Category}." );
					}
				} else {
					$mvc->addObject ( ContentController::ERROR, "_i18n{SubCategory name can't be empty.}" );
				}
			}
			$subcategory = SubCategoryService::getSubCategory($id);
			isset($subcategory) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory) : NULL;
			
			$categories = CategoryService::getCategories();
			isset($categories) ? $mvc->addObject ( CategoryService::CATEGORY, $categories) : NULL;
		
		}
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
		
		$where = NULL;
		#user sorting
		$where .= isset ( $requestParams [UsersService::ROLE]) ? 
				UsersService::ROLE . "= '" . $requestParams [UsersService::ROLE] . "'" : NULL;
		$where .= $where == NULL ? NULL : ' AND ';
		$where .= isset ( $requestParams [UsersService::SCROLLER]) ? 
				"UCASE( LEFT (lastname, 1 ) )" . "= '" . $requestParams [UsersService::SCROLLER] . "' AND " : NULL;
		
		$where .= UsersService::DELETED . " = 0";// AND " . UsersService::ROLE . "!='" . UsersService::ROLE_UR . "'";
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$where .= " and " . UsersService::USERS . '.' .UsersService::ID . "!=" . $id;
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
			$where = UsersService::USERS . '.' . UsersService::ID . " = '" . $requestParams [UsersService::ID] . "'";
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
		$location = $this->onSuccess( $actionParams );
		
		isset($requestParams['cancel']) ? $this->forwardActionRequest ( $location ) : NULL;
		
		if (isset ( $requestParams ['submit'] )) {
			//server-side validation
			$error = UsersService::validation ( $requestParams );
			if (count ( $error ) == 0) {
				// Insert new users to DB
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

