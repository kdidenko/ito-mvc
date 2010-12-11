<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class TradesmanContentController extends ContentController {
	
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
							//'3'=>UsersService::COMPANY,
							//'4'=>UsersService::VAT,
							'5'=>UsersService::ADDRESS,
							'6'=>UsersService::ZIP,
							'7'=>UsersService::LOCATION,
							'8'=>UsersService::REGION,
							'9'=>UsersService::COUNTRY,
							'10'=>UsersService::PHONE,
							'11'=>UsersService::HOMEPAGE,
							'12'=>UsersService::CAT_ID,
							'13'=>UsersService::SUBCAT_ID,
							'14'=>UsersService::SALUTATION//,
							//'13'=>UsersService::COMPANY_YEAR
							);
			$vals = array (
							'0'=>$requestParams [UsersService::FIRSTNAME], 
							'1'=>$requestParams [UsersService::LASTNAME],
							'2'=>$requestParams [UsersService::EMAIL],
							//'3'=>$requestParams [UsersService::COMPANY],
							//'4'=>$requestParams [UsersService::VAT],
							'5'=>$requestParams [UsersService::ADDRESS],
							'6'=>$requestParams [UsersService::ZIP],
							'7'=>$requestParams [UsersService::LOCATION],
							'8'=>$requestParams [UsersService::REGION],
							'9'=>$requestParams [UsersService::COUNTRY],
							'10'=>$requestParams [UsersService::PHONE],
							'11'=>$requestParams [UsersService::HOMEPAGE],
							'12'=>$requestParams [CategoryService::CATEGORY],
							'13'=>$requestParams [SubCategoryService::SUBCATEGORY],
							'14'=>$requestParams [UsersService::SALUTATION]//,
							//'13'=>$requestParams [UsersService::COMPANY_YEAR]
							);
			
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( self::STATUS, 'successful' );
			$this->forwardActionRequest ( $location );
		}
		
		#get user info
		$result = UsersService::getUser ( $id );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;

		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;
		
		$subcategory = SubCategoryService::getSubcatByCat ($result[UsersService::CAT_ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		return $mvc;
	}
	
	public function handleCompanyProfile($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$username = SessionService::getAttribute ( UsersService::USERNAME );
		$error = array ();
		
		
		if (isset ( $requestParams ['companyInfoSbm'] )) {
			$fields = array ('0'=>UsersService::COMPANY_DESC);
			$vals = array ('0'=>$requestParams [UsersService::COMPANY_DESC]);
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( self::STATUS, 'successful' );
		}
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
					$mvc->addObject ( self::STATUS, 'successful' );
					self::setNoCashe();
				}
			} else {
				$mvc->addObject ( self::IMAGE_ERROR, $error );
			}
		}
		
		if (isset ( $requestParams ['personalSbm'] )) {		
			$fields = array (
							'0'=>UsersService::FIRSTNAME,
							'1'=>UsersService::LASTNAME,
							'2'=>UsersService::SALUTATION,
							'3'=>UsersService::PHONE,
							'4'=>UsersService::EMAIL
							);
			$vals = array (
							'0'=>$requestParams [UsersService::FIRSTNAME], 
							'1'=>$requestParams [UsersService::LASTNAME],
							'2'=>$requestParams [UsersService::SALUTATION],
							'3'=>$requestParams [UsersService::PHONE],
							'4'=>$requestParams [UsersService::EMAIL]
							);
			
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( self::STATUS, 'successful' );
		}
		
		#get user info
		$result = UsersService::getUser ( $id );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		return $mvc;
	}
	
	public function handleCommunication($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		if(isset($requestParams['save'])){
			if (isset($requestParams['region'])){
				if (isset($requestParams[CategoryService::CATEGORY]) && count($requestParams[CategoryService::CATEGORY])>0){
					$value = array();
					$categories = $requestParams[CategoryService::CATEGORY];
					$subcategories = $requestParams[SubCategoryService::SUBCATEGORY];
					$plan = $requestParams[PlanService::PLAN];
					$region = $requestParams['region'];
					
					RemindService::deleteByUser($id);
					
					foreach ($region as $region_key=>$region_item){
						foreach($categories as $key=>$category){
							$subcategory = $subcategories[$key];
							$price = 1;
							$region = $region_item;
							$values[] .= $id . ', ' . $category . ', ' . $subcategory . ', ' . 
										$price . ', ' . $region;
						}
					}
					
					RemindService::setRemind($id, $values);
					
					$mvc->addObject ( self::STATUS, 'successful' );
				} else {
					$mvc->addObject ( self::ERROR, '<br/>ERROR no notification' );
				}
			} else {
				if (!isset($requestParams[CategoryService::CATEGORY])) {
					RemindService::deleteByUser($id);
				} else {
					$mvc->addObject ( self::ERROR, '<br/>ERROR no region' );
				}
			}
		}	
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		//$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		$subcategory = SubCategoryService::getSubCategories ();
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		
		$data = RemindService::getRemindsByUser ($id);
		isset ( $data ) ? $mvc->addObject ( self::RESULT, $data ) : null;
		
		$regions = RegionService::getRegions ($id);
		isset ( $regions ) ? $mvc->addObject ( RegionService::REGIONS, $regions ) : null;
		
		$reminds_regions = RemindService::getRemindsRegionsByUser ($id);
		isset ( $reminds_regions ) ? $mvc->addObject ( RemindService::REGION_ID, $reminds_regions ) : null;
		
		return $mvc;
	}
	
	public function handleMyBargains($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			//MailService::deleteMails ($requestParams ['itemSelect'])
			print_r($requestParams [self::DEL_ALL]) :
				null;
		
		$bargains = BargainsService::getBargains($id);
		isset ( $bargains ) ? $mvc->addObject ( BargainsService::BARGAINS, $bargains ) : null;
		
		return $mvc;
	}
	
	public function handleMyPlan($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if(isset($requestParams['submit'])){
			$id = SessionService::getAttribute(SessionService::USERS_ID);
			$fields = UsersService::PLAN_ID;
			$value = $requestParams[UsersService::PLAN_ID];
			UsersService::updateFields($id, $fields, $value);
			SessionService::setAttribute(SessionService::PLAN_ID, $requestParams[UsersService::PLAN_ID]);
		}
		$plans = PlanService::getPlans();
		isset($plans) ? $mvc->addObject ( PlanService::PLAN, $plans ) : NULL;
		
		$plan = SessionService::getAttribute(SessionService::PLAN_ID);
		$plan = isset($requestParams[PlanService::PLAN])? $requestParams[PlanService::PLAN] :$plan; 
		$mvc->addObject ( PlanService::CRNT_PLAN, $plan);
		
		
		
		
	return $mvc;
	}
}
?>