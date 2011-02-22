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
		$catId = isset($result[UsersService::CAT_ID])&&$result[UsersService::CAT_ID]!=NULL ?
					$result[UsersService::CAT_ID] : 1;
		$subcategory = SubCategoryService::getSubcatByCat ($catId);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
		return $mvc;
	}
	
	public function handleCompanyProfile($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$username = SessionService::getAttribute ( UsersService::USERNAME );
		$error = array ();
		
		//print_r($requestParams);
		if (isset ( $requestParams ['companyInfoSbm'] )) {
			$fields = array ('0'=>UsersService::COMPANY_DESC);
			$vals = array ('0'=>$requestParams [UsersService::COMPANY_DESC]);
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( self::STATUS, 'successful' );
		}
		if (isset ( $requestParams ['pctSbm'] )) {
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$file = $_FILES ['file'];
				$path = StorageService::USERS_FOLDER . $username . StorageService::USER_PROFILE . "avatar.jpg";
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
		
		if(isset($requestParams['deleteReferences'])&&$requestParams['deleteReferences']!=NULL){
			CompanyService::delReference($id, $requestParams['deleteReferences']);
		}
	
		if(isset($requestParams['deleteProject'])&&$requestParams['deleteProject']!=NULL){
			CompanyService::delPoject($id, $requestParams['deleteProject']);
		}
		
		if(isset($requestParams['deleteCertificates'])&&$requestParams['deleteCertificates']!=NULL){
			CompanyService::delCertificate($id, $requestParams['deleteCertificates']);
		}
		
		#get user info
		$result = UsersService::getUser ( $id );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		
		$where = CompanyService::COMPANY_ID . '=' . $id . ' AND ' . CompanyService::DONE . '=1';
		$feedbacks = CompanyService::getFeedback ($where);
		isset ( $feedbacks ) ? $mvc->addObject ( CompanyService::COMPANY_FEEDBACK, $feedbacks ) : null;
		
		$projects = CompanyService::getProjects ($id);
		isset ( $projects ) ? $mvc->addObject ( CompanyService::COMPANY_PROJECT, $projects ) : null;
		
		$certificates = CompanyService::getCertificates ($id);
		isset ( $certificates ) ? $mvc->addObject ( CompanyService::COMPANY_CARTIFICATES, $certificates ) : null;
		
		$references = CompanyService::getReferences ($id);
		isset ( $references ) ? $mvc->addObject ( CompanyService::COMPANY_REFERENCES, $references ) : null;

		return $mvc;
	}
	
	public function handleNewProfileProject($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($_POST)&&$_POST!=NULL){
			$error = array();
			$error[] .= !isset($requestParams[CompanyService::PROJECT_TITLE])||$requestParams[CompanyService::PROJECT_TITLE]==NULL ? "_i18n{Please, write short description.}" : false;
			$error[] .= !isset($requestParams[CompanyService::PROJECT_URL])||$requestParams[CompanyService::PROJECT_URL]==NULL ? "_i18n{Please, write correct link.}" : false;
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				$id = SessionService::getAttribute(SessionService::USERS_ID);
				$project_url = htmlspecialchars($requestParams [CompanyService::PROJECT_URL], ENT_QUOTES);
				$project_title = htmlspecialchars($requestParams [CompanyService::PROJECT_TITLE], ENT_QUOTES);
				CompanyService::setPoject($id, $project_url,$project_title);
				$mvc->addObject ( 'success', true );
			} else {
				$mvc->addObject ( 'error', $error );
			}
		}
		return $mvc;
	}
	
	public function handleNewProfileImage($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset($_POST)&&$_POST!=NULL){
			$error = array();
			//print_r($_FILES);
			//print_r($requestParams);
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$username = SessionService::getAttribute(SessionService::USERNAME);
				$file = $_FILES ['file'];
				$path = StorageService::USERS_FOLDER . $username . StorageService::USER_CARTIFICATES . $file['name'];
				$error[] .= ValidationService::checkFileSize ( $file );
			} else {
				$error[] .= "_i18n{Please, upload file.}";
			}
			
			$error[] .= !isset($requestParams[CompanyService::REFERENCE_TITLE])||$requestParams[CompanyService::REFERENCE_TITLE]==NULL ? "_i18n{Please, write short description.}" : false;
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				$id = SessionService::getAttribute(SessionService::USERS_ID);
				$cartificates_title = htmlspecialchars($requestParams [CompanyService::REFERENCE_TITLE], ENT_QUOTES);
				StorageService::uploadFile ( $path, $file );
				$upload_id = UploadsService::setUploadsPath($path);
				CompanyService::setReference($id, $upload_id, $cartificates_title);
				$mvc->addObject ( 'success', true );
			} else {
				$mvc->addObject ( 'error', $error );
			}
		}
		
		return $mvc;
	}
	
	public function handleNewProfileCertificate($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
	
		if (isset($_POST)&&$_POST!=NULL){
			$error = array();
			//print_r($_FILES);
			//print_r($requestParams);
			if (isset ( $_FILES ['file'] ['name'] ) && $_FILES ['file'] ['error'] == 0) {
				$username = SessionService::getAttribute(SessionService::USERNAME);
				$file = $_FILES ['file'];
				$path = StorageService::USERS_FOLDER . $username . StorageService::USER_CARTIFICATES . $file['name'];
				$error[] .= ValidationService::checkFileSize ( $file );
			} else {
				$error[] .= "_i18n{Please, upload file.}";
			}
			
			$error[] .= !isset($requestParams[CompanyService::CARTIFICATES_TITLE])||$requestParams[CompanyService::CARTIFICATES_TITLE]==NULL ? "_i18n{Please, write short description.}" : false;
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				$id = SessionService::getAttribute(SessionService::USERS_ID);
				$cartificates_title = htmlspecialchars($requestParams [CompanyService::CARTIFICATES_TITLE], ENT_QUOTES);
				StorageService::uploadFile ( $path, $file );
				$upload_id = UploadsService::setUploadsPath($path);
				CompanyService::setCertificate($id, $upload_id, $cartificates_title);
				$mvc->addObject ( 'success', true );
			} else {
				$mvc->addObject ( 'error', $error );
			}
		}
		
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
					$prices = $requestParams[PlanService::PLAN];
					$plan = $requestParams[PlanService::PLAN];
					$region = $requestParams['region'];
					
					RemindService::deleteByUser($id);
					
					foreach ($region as $region_key=>$region_item){
						foreach($categories as $key=>$category){
							$subcategory = $subcategories[$key];
							$price = $prices[$key];
							$region = $region_item;
							$values[] .= $id . ', ' . $category . ', ' . $subcategory . ', ' . 
										$price . ', ' . $region;
						}
					}
					
					RemindService::setRemind($id, $values);
					
					$mvc->addObject ( self::STATUS, 'successful' );
				} else {
					$mvc->addObject ( self::ERROR, '_i18n{Please, create new notification}' );
				}
			} else {
				if (!isset($requestParams[CategoryService::CATEGORY])) {
					RemindService::deleteByUser($id);
				} else {
					$mvc->addObject ( self::ERROR, '_i18n{Please, select one or more regions}' );
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
		
		$plans = PlanService::getPlans();
		isset ( $plans ) ? $mvc->addObject ( PlanService::PLAN, $plans ) : null;
		
		return $mvc;
	}
	
	public function handleMyBargains($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$plan_id = SessionService::getAttribute(SessionService::PLAN_ID);
		$plan_info = PlanService::getPlan($plan_id);
		if ($plan_info[PlanService::BARGAINS]==1){
			isset ( $requestParams [UsersService::ENABLED] ) ? BargainsService::updateFields ( $requestParams [UsersService::ENABLED], BargainsService::STATUS, '1' ) : NULL;
			isset ( $requestParams [UsersService::DISABLE] ) ? BargainsService::updateFields ( $requestParams [UsersService::DISABLE], BargainsService::STATUS, '0' ) : NULL;
			
			isset ( $requestParams [self::DEL_ALL] ) ? 
				BargainsService::deleteBargains ($requestParams ['itemSelect']) :
					null;
			
			$bargains = BargainsService::getBargains($id);
			isset ( $bargains ) ? $mvc->addObject ( BargainsService::BARGAINS, $bargains ) : null;
		}else{
			$mvc->addObject ( ContentController::ERROR, "_i18n{You have no access yet. Please, upgrade your} <a href='/my-plan.html' title='_i18n{Change Plan}'>_i18n{plan}</a>." );
		}
		return $mvc;
	}
	
	
	public function handleNewBargain($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if(isset($requestParams['save'])){
			$error = array();
			foreach ($requestParams as $key => $value){
				$error[] .= $value==NULL ? true : false;
			}
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				
				$file = $_FILES ['bargain_image'];
				if ($file['error']==0){
					$date = mktime();
					$height = 100;
					$width = 100;
					$path = StorageService::BARGAINS_FOLDER . "bargain-$date.jpg";
					$path2 = StorageService::BARGAINS_FOLDER . "bargain-$date-" . ImageService::SMALL . ".jpg";
					if (isset ( $file ['name'] ) ) {
						StorageService::uploadFile ( $path, $file );
						self::setNoCashe();
						$image = new ImageService();
						$image->load($path);
						$image->resize($width,$height);
						$image->save($path2);
					}
					$id = SessionService::getAttribute(SessionService::USERS_ID);
					$from_date = explode('/', $requestParams[BargainsService::FROM_DATE]);
					$until_date = explode('/', $requestParams[BargainsService::UNTIL_DATE]);
					$requestParams[BargainsService::FROM_DATE] = $from_date[2] . '-' . $from_date[1] . '-' . $from_date[0];
					$requestParams[BargainsService::UNTIL_DATE] = $until_date[2] . '-' . $until_date[1] . '-' . $until_date[0];
					$requestParams[BargainsService::BARGAIN_DESC] = htmlspecialchars($requestParams[BargainsService::BARGAIN_DESC], ENT_QUOTES);
					BargainsService::setBargain($id, $requestParams, $path);
				}
			} else {
				$mvc->addObject ( self::ERROR, '_i18n{Please, fill in all fields.}' );
			}
		}
		
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
		return $mvc;
	}
	
	public function handleEditBargain($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if(isset($requestParams['save'])){
			$error = array();
			foreach ($requestParams as $key => $value){
				$error[] .= $value==NULL ? true : false;
			}
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				$from_date = explode('/', $requestParams[BargainsService::FROM_DATE]);
				$until_date = explode('/', $requestParams[BargainsService::UNTIL_DATE]);
				$fields = array();
				$fields[] .= $requestParams[BargainsService::BARGAIN_NAME];
				$fields[] .= htmlspecialchars($requestParams[BargainsService::BARGAIN_DESC], ENT_QUOTES);
				$fields[] .= $requestParams[BargainsService::CATEGORY_ID];
				$fields[] .= $requestParams[BargainsService::SUBCATEGORY_ID];
				$fields[] .= $requestParams[BargainsService::USUAL_PRICE];
				$fields[] .= $requestParams[BargainsService::BARGAIN_PRICE];
				$fields[] .= $requestParams[BargainsService::NUMBER];
				$fields[] .= $requestParams[BargainsService::COUNTRY];
				$fields[] .= $requestParams[BargainsService::REGION];
				$fields[] .= $requestParams[BargainsService::CITY];
				$fields[] .= $requestParams[BargainsService::ZIP];
				$fields[] .= $requestParams[BargainsService::STREET];
				$fields[] .= $requestParams[BargainsService::WEBSITE];
				$fields[] .= $from_date[2] . '-' . $from_date[1] . '-' . $from_date[0];
				$fields[] .= $until_date[2] . '-' . $until_date[1] . '-' . $until_date[0];
				$vals = array();
				$vals[] .= BargainsService::BARGAIN_NAME;
				$vals[] .= BargainsService::BARGAIN_DESC;
				$vals[] .= BargainsService::CATEGORY_ID;
				$vals[] .= BargainsService::SUBCATEGORY_ID;
				$vals[] .= BargainsService::USUAL_PRICE;
				$vals[] .= BargainsService::BARGAIN_PRICE;
				$vals[] .= BargainsService::NUMBER;
				$vals[] .= BargainsService::COUNTRY;
				$vals[] .= BargainsService::REGION;
				$vals[] .= BargainsService::CITY;
				$vals[] .= BargainsService::ZIP;
				$vals[] .= BargainsService::STREET;
				$vals[] .= BargainsService::WEBSITE;
				$vals[] .= BargainsService::FROM_DATE;
				$vals[] .= BargainsService::UNTIL_DATE;
				BargainsService::updateFields($requestParams[BargainsService::ID], $vals, $fields);
				$file = $_FILES ['bargain_image'];
				if ($file['error']==0){
					$date = mktime();
					$height = 100;
					$width = 100;
					$path = $requestParams[UploadsService::PATH];
					$path2 = $requestParams[UploadsService::PATH2];
					if (isset ( $file ['name'] ) ) {
						StorageService::uploadFile ( $path, $file );
						self::setNoCashe();
						$image = new ImageService();
						$image->load($path);
						$image->resize($width,$height);
						$image->save($path2);
					}
				}
				$mvc->addObject ( self::STATUS, "_i18n{You have successfully added a bookmark!}" );
				//$id = SessionService::getAttribute(SessionService::USERS_ID);
				//BargainsService::updateFields($id, $requestParams, $path);
			} else {
				$mvc->addObject ( self::ERROR, '_i18n{Please, fill in all fields.}' );
			}
		}
		
		$where = BargainsService::HASH . "='" . $requestParams[BargainsService::ID] . "'";
		$bargain = BargainsService::getBargains ($where);
		if ($bargain[0][UploadsService::PATH]!=NULL){
			$part = explode('.',$bargain[0][UploadsService::PATH]);
			$bargain[0][UploadsService::PATH2] = $part[0] . '-thumbnail.' . $part[1];
		}
		$from_date = explode('-', $bargain[0][BargainsService::FROM_DATE]);
		$until_date = explode('-', $bargain[0][BargainsService::UNTIL_DATE]);
		$bargain[0][BargainsService::FROM_DATE] = $from_date[2] . '/' . $from_date[1] . '/' . $from_date[0];
		$bargain[0][BargainsService::UNTIL_DATE] = $until_date[2] . '/' . $until_date[1] . '/' . $until_date[0];
		$bargain[0][BargainsService::BARGAIN_DESC] = htmlspecialchars_decode($bargain[0][BargainsService::BARGAIN_DESC], ENT_QUOTES);
		isset ( $bargain ) ? $mvc->addObject ( BargainsService::BARGAINS, $bargain[0] ) : null;
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$cat_id = isset($bargain[0][BargainsService::CATEGORY_ID]) ? $bargain[0][BargainsService::CATEGORY_ID] : $category[0][CategoryService::ID];
		$subcategory = SubCategoryService::getSubcatByCat ($cat_id);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
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
	
	public function handleMyOrder($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$date = date("Y-m-d");
		
		$where = OrdersService::ORDERS . '.' . OrdersService::IMP_UNTIL_DATE . ">='" . $date . "'";
		$won_orders = OrdersService::getBoughtOrder($id,$where);
		isset($won_orders) ? $mvc->addObject ( OrdersService::BOUGHT_ORDERS, $won_orders) : NULL;

		$where = OrdersService::ORDERS . '.' . OrdersService::IMP_UNTIL_DATE . "<'" .  $date . "'";
		$won_orders_archiv = OrdersService::getBoughtOrder($id,$where);
		isset($won_orders_archiv) ? $mvc->addObject ( OrdersService::BOUGHT_ORDERS . '_archiv', $won_orders_archiv) : NULL;
		
		$crnt_orders = OrdersService::getCurrentOrders($id);
		isset($crnt_orders) ? $mvc->addObject ( OrdersService::BIDS, $crnt_orders) : NULL;
		
		$bookmarks = OrdersService::getOrderBookmarks($id);
		isset($bookmarks) ? $mvc->addObject ( OrdersService::ORDER_BOOKMARKS, $bookmarks) : NULL;
		return $mvc;
	}
	
}
?>