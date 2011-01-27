<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	const DEL = 'del';
	
	const DEL_ALL = 'delAll';
	
	const ERROR = 'error';
	
	const PSW_ERROR = 'psw_error';
	
	const IMAGE_ERROR = 'image_errors';
	
	const STATUS = 'status';
	
	const RESULT = 'result';
	/**
	 * @var string defines the user details constant
	 */
	const USER_DETAILS = 'USER';
	
	/*
	 * HOME PAGE
	 */
	public function handleHome($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;

		$regions = RegionService::getRegions ();
		isset ( $regions ) ? $mvc->addObject ( RegionService::REGIONS, $regions ) : null;
		
		return $mvc;
	}
	
	public function handleForEntrepreneurs($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;

		return $mvc;
	}

	public function handleCompanies($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$where = UsersService::ROLE . "='" . UsersService::ROLE_TR . "'";
		$onpage = isset ( $requestParams ['onpage'] )&& $requestParams ['onpage'] !=NULL ? $requestParams ['onpage'] : 5;
		if (isset ( $requestParams ['page'] )&& $requestParams ['page'] !=NULL ){ 
			$limit = ($requestParams ['page']-1)*$onpage . "," . $onpage;
		} else {
			$limit = "0,$onpage";
		}
		if (isset ( $requestParams [UsersService::COMPANY] )&& $requestParams [UsersService::COMPANY] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= UsersService::USERS . '.' . UsersService::COMPANY . " LIKE '%" . $requestParams [UsersService::COMPANY] . "%'";
		}
		if (isset ( $requestParams [UsersService::COUNTRY] ) && $requestParams [UsersService::COUNTRY]!='all' ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= UsersService::USERS . '.' . UsersService::COUNTRY . '=' . $requestParams [UsersService::COUNTRY];
		}
		if (isset ( $requestParams [UsersService::REGION] ) && $requestParams [UsersService::REGION]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= UsersService::USERS . '.' . UsersService::REGION . '=' . $requestParams [UsersService::REGION];
		}
		if (isset ( $requestParams [UsersService::CAT_ID] ) && $requestParams [UsersService::CAT_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= UsersService::USERS . '.' . UsersService::CAT_ID . '=' . $requestParams [UsersService::CAT_ID];
		}
		if (isset ( $requestParams [UsersService::SUBCAT_ID] ) && $requestParams [UsersService::SUBCAT_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= UsersService::USERS . '.' . UsersService::SUBCAT_ID . '=' . $requestParams [UsersService::SUBCAT_ID];
		}
		$users = UsersService::getUsersList ($where, null, true);
		foreach($users as $key =>$company){
			$users[$key][UsersService::COMPANY_DESC] = self::breakword($company[UsersService::COMPANY_DESC],255);
		}
		isset ( $users ) ? $mvc->addObject ( UsersService::USERS, $users ) : null;
		
		$all_users = UsersService::countUsers ($where);
		isset ( $users ) ? $mvc->addObject ( "count",  $all_users[UsersService::USERS] ) : null;
		
		$pages = $all_users[UsersService::USERS]/$onpage; 
		isset ( $users ) ? $mvc->addObject ( "pages",  $pages ) : null;
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		if (isset ($requestParams[UsersService::SUBCAT_ID])){
			$crntCategory = $requestParams[UsersService::SUBCAT_ID]!='all' ? 
								$requestParams[UsersService::SUBCAT_ID] : 
									$category[0][CategoryService::ID];  
			$subcategory = SubCategoryService::getSubcatByCat ($crntCategory);
			isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		}
		
		$regions = RegionService::getRegions ();
		isset ( $regions ) ? $mvc->addObject ( RegionService::REGIONS, $regions ) : null;
		
		$countries = CountryService::getCountries ();
		isset ( $countries ) ? $mvc->addObject ( CountryService::COUNTRY, $countries ) : null;
		
		return $mvc;
	}

	public function handleCompany($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[UsersService::ID])){
			if (isset($requestParams[CompanyService::VOTE])){
				$user_id = SessionService::getAttribute(SessionService::USER_ID);
				$company_id = $requestParams[UsersService::ID];
				$vote = $requestParams[CompanyService::VOTE];
				$comment = $requestParams[CompanyService::COMMENT];
				CompanyService::feedbackCompany($user_id,$company_id,$vote,$comment);
			}
			
			$users = UsersService::getUser ($requestParams[UsersService::ID], true);
			isset ( $users )&&$users[UsersService::ROLE]==UsersService::ROLE_TR ? $mvc->addObject ( UsersService::USERS, $users ) : null;
	
		}	
		return $mvc;
	}
	
	public function handleNewOrder($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$role = SessionService::getAttribute(SessionService::ROLE);
		if(isset($requestParams['save'])){
			$error = array();
			$files = $_FILES ['file'];
			foreach ($requestParams as $key => $value){
				$error[] .= $value==NULL ? true : false;
			}
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				if($files['error'][1]==0 || $files['error'][2]==0 || $files['error'][3]==0 || $files['error'][4]==0){
					if($role==SessionService::ROLE_UR){
						$img = array();
						$paths = array();
						foreach ($files['error'] as $key=>$error){
							if($error==0){
								$img['name'] = $files['name'][$key];
								$img['type'] = $files['type'][$key];
								$img['tmp_name'] = $files['tmp_name'][$key];
								$img['error'] = $files['error'][$key];
								$img['size'] = $files['size'][$key];
								
								$date = mktime();
								$height = 100;
								$width = 100;
								$path = StorageService::ORDERS_FOLDER . "order-$date-$key.jpg";
								$paths [].= $path;
								$path2 = StorageService::ORDERS_FOLDER . "order-$date-$key-" . ImageService::SMALL . ".jpg";
								if (isset ( $img ['name'] ) ) {
									StorageService::uploadFile ( $path, $img );
									self::setNoCashe();
									$image = new ImageService();
									$image->load($path);
									$image->resize($width,$height);
									$image->save($path2);
								}
							}
						}
										
						$id = SessionService::getAttribute(SessionService::USERS_ID);
						$requestParams[OrdersService::IMP_FROM_DATE] = self::createDate($requestParams[OrdersService::IMP_FROM_DATE]);
						$requestParams[OrdersService::IMP_UNTIL_DATE] = self::createDate($requestParams[OrdersService::IMP_UNTIL_DATE]);
						$requestParams[OrdersService::FROM_DATE] = self::createDate($requestParams[OrdersService::FROM_DATE]);
						$requestParams[OrdersService::UNTIL_DATE] = self::createDate($requestParams[OrdersService::UNTIL_DATE]);
						$requestParams[OrdersService::ORDER_DESC] = htmlspecialchars($requestParams[OrdersService::ORDER_DESC], ENT_QUOTES);
						OrdersService::setOrders($id, $requestParams, $paths);
						$location = $this->onSuccess( $actionParams );
						$this->forwardActionRequest ( $location );
					} else {	
						$mvc->addObject ( self::ERROR, "_i18n{Please}, <a href='/login.html' title='_i18n{login}' target='blank'>_i18n{login like user}</a> _i18n{if you alredy registred, or you can} <a href='/registration.html?role=1' title='_i18n{register}' target='blank'>_i18n{register like user}</a>." );
					}
				} else {	
					$mvc->addObject ( self::ERROR, "_i18n{Please, upload one or more images.}" );
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
	
	public function handleViewOrders($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		//check 
		$result = OrdersService::checkOrders();
		if($result!=NULL){
			foreach($result as $key => $order){
				$bids = OrdersService::buyOrder ($order[OrdersService::ID]);
				
				if(isset($bids)&&$bids!=NULL){
					$user_id = $order[OrdersService::OWNER];
					$company_id = $bids[0][OrdersService::USER_ID];
					$order_id = $order[OrdersService::ID];
					CompanyService::feedbackCompany($user_id, $company_id, $order_id);
					$subject = $order[OrdersService::ORDER_NAME];
					$sender_id = 4;
					$user = SessionService::getAttribute ( SessionService::USERNAME );
					$text = "<b>Congratulation!</b><br\/> You have just won auction <a href=\'/view-bargain.html?id=" . $order[OrdersService::HASH] . "\'>" . $order[OrdersService::ORDER_NAME] . "<\/a>!";
					$getter_id = $bids[0][OrdersService::USER_ID];
					#prepare text for email 
					$plain = $mvc->getProperty ( 'newMessage' );
					MailService::sendMail($subject, $text, $sender_id, $getter_id, $plain);
				}
			}
		}
		
		$where = OrdersService::BOUGHT . '=0';
		$onpage = isset ( $requestParams ['onpage'] )&& $requestParams ['onpage'] !=NULL ? $requestParams ['onpage'] : 5;
		if (isset ( $requestParams ['page'] )&& $requestParams ['page'] !=NULL ){ 
			$limit = ($requestParams ['page']-1)*$onpage . "," . $onpage;
		} else {
			$limit = "0,$onpage";
		}
		if (isset ( $requestParams [OrdersService::ORDER_NAME] )&& $requestParams [OrdersService::ORDER_NAME] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::ORDER_NAME . " LIKE '%" . $requestParams [OrdersService::ORDER_NAME] . "%'";
		}
		if (isset ( $requestParams [OrdersService::FROM_PRICE] )&& $requestParams [OrdersService::FROM_PRICE] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::PRICE . ' >= ' . $requestParams [OrdersService::FROM_PRICE];
		}
		if (isset ( $requestParams [OrdersService::UNTIL_PRICE] )&& $requestParams [OrdersService::UNTIL_PRICE] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::PRICE . ' <= ' . $requestParams [OrdersService::UNTIL_PRICE];
		}
		if (isset ( $requestParams [OrdersService::COUNTRY] ) && $requestParams [OrdersService::COUNTRY]!='all' ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::COUNTRY . '=' . $requestParams [OrdersService::COUNTRY];
		}
		if (isset ( $requestParams [OrdersService::REGION] ) && $requestParams [OrdersService::REGION]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::REGION . '=' . $requestParams [OrdersService::REGION];
		}
		if (isset ( $requestParams [OrdersService::CATEGORY_ID] ) && $requestParams [OrdersService::CATEGORY_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::CATEGORY_ID . '=' . $requestParams [OrdersService::CATEGORY_ID];
		}
		if (isset ( $requestParams [OrdersService::SUBCATEGORY_ID] ) && $requestParams [OrdersService::SUBCATEGORY_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= OrdersService::ORDERS . '.' . OrdersService::SUBCATEGORY_ID . '=' . $requestParams [OrdersService::SUBCATEGORY_ID];
		}
		$orders = OrdersService::getOrders ($where, $limit);
		if ($orders!=NULL){
			foreach($orders as $key => $value){
				if ($value[UploadsService::PATH]!=NULL){
					$part = explode('.',$value[UploadsService::PATH]);
					$orders[$key][UploadsService::PATH] = $part[0] . '-thumbnail.' . $part[1];
				} else {
					$orders[$key][UploadsService::PATH] = StOrageService::DEF_ORDER_AVATAR;
				}
			}
		}
		isset ( $orders ) ? $mvc->addObject ( OrdersService::ORDERS, $orders ) : null;
		
		$all_orders = OrdersService::countOrders ($where);
		isset ( $orders ) ? $mvc->addObject ( "count",  $all_orders[OrdersService::ORDERS] ) : null;
		
		$pages = $all_orders[OrdersService::ORDERS]/$onpage; 
		isset ( $orders ) ? $mvc->addObject ( "pages",  $pages ) : null;
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		if (isset ($requestParams[OrdersService::CATEGORY_ID])){
			$crntCategory = $requestParams[OrdersService::CATEGORY_ID]!='all' ? 
								$requestParams[OrdersService::CATEGORY_ID] : 
									$category[0][CategoryService::ID];  
			$subcategory = SubCategoryService::getSubcatByCat ($crntCategory);
			isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		}
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
		return $mvc;
	}
	
	public function handleViewOrder($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		
		if(isset($requestParams[OrdersService::ID])){
			$where = OrdersService::HASH . "='" . $requestParams[OrdersService::ID] . "'";
			$order = OrdersService::getOrders ($where);
			if(isset ( $order )&&$order!=NULL) {
				$order[0]['time_left'] = self::countTimeLeft($order[0]['until_date']);
				$mvc->addObject ( OrdersService::ORDERS, $order[0] );
			}
		
		
			if (isset ($requestParams['addBookmark']) || isset ($requestParams['delBookmark'])){
				$role = SessionService::getAttribute ( SessionService::ROLE );
				if ($role!=NULL){
					if ($role==UsersService::ROLE_TR){
						if (isset ($requestParams['addBookmark'])){
							OrdersService::createBookmark($order[0][OrdersService::ID], $id);
							$mvc->addObject ( self::STATUS, "_i18n{You have successfully added a bookmark!}" );
						} else {
							OrdersService::removeBookmark($order[0][OrdersService::ID], $id);
							$mvc->addObject ( self::STATUS, "_i18n{You have successfully removed a bookmark!}" );
						}
					}
				} else {
					$mvc->addObject ( self::ERROR, "_i18n{Please}, <a href='/login.html' title='_i18n{login}'>_i18n{login}</a> _i18n{if you alredy registred, or you can} <a href='/registration.html' title='_i18n{register}'>_i18n{register}</a>." );
				}
			}
			
			if (isset($requestParams['makeBid']) && $requestParams[OrdersService::BID]!=NULL){
				$role = SessionService::getAttribute ( SessionService::ROLE );
				if ($role!=NULL){
					if ($role==UsersService::ROLE_TR){
						if ($order[0][OrdersService::PRICE]>=$requestParams[OrdersService::BID]){
							$where = OrdersService::BID . '<=' . $requestParams[OrdersService::BID]; 
							$smaller = OrdersService::getBids($order[0][OrdersService::ID], $where);
							if ($smaller ==NULL && count($smaller)>=1){
								OrdersService::makeBid($requestParams[OrdersService::ID], $requestParams[OrdersService::BID]);
								$mvc->addObject ( self::STATUS, "_i18n{You bid successfully saved!}" );
							} else {
								$mvc->addObject ( self::ERROR, "_i18n{Your bid should be smaller then current bid} ".$smaller[0]['bid'] . " &euro;" );
							}
						} else {
							$mvc->addObject ( self::ERROR, "_i18n{Your bid should be smaller then order price} ".$order[0][OrdersService::PRICE] . " &euro;" );
						}
					} else {
						$mvc->addObject ( self::ERROR, "_i18n{Only tradesman can make a bid, you can} <a href='/registration.html?role=2' title='_i18n{register}'>_i18n{register}</a> like tradesman." );
					}
				} else {
					$mvc->addObject ( self::ERROR, "_i18n{Please}, <a href='/login.html' title='_i18n{login}'>_i18n{login}</a> _i18n{if you alredy registred, or you can} <a href='/registration.html' title='_i18n{register}'>_i18n{register}</a>." );
				}
			}
			
			
			$images = OrdersService::getOrderImgs ($order[0][OrdersService::ID]);
			if ($images!=NULL){
				foreach($images as $key => $value){
					$part = explode('.',$value[UploadsService::PATH]);
					$images[$key][UploadsService::PATH2] = $part[0] . '-thumbnail.' . $part[1];
				}
			} else {
				$images[0][UploadsService::PATH] = StOrageService::DEF_ORDER_AVATAR;
			}
			isset ( $images ) ? $mvc->addObject ( UploadsService::PATH, $images ) : null;
			
			$bids = OrdersService::getBids($order[0][OrdersService::ID]);
			isset ( $bids ) ? $mvc->addObject ( OrdersService::BIDS, $bids ) : null;
			
			$bookmark = OrdersService::getOrderBookmarks($id, $order[0][OrdersService::ID]);
			isset ( $bookmark ) ? $mvc->addObject ( OrdersService::ORDER_BOOKMARKS, $bookmark ) : null;
		}
		return $mvc;
	}
	
	public function handleViewBargains($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$date = date("Y-m-d h:m:s");
		$where = BargainsService::UNTIL_DATE . ">='" . $date . "' AND " . BargainsService::NUMBER . '>0' . " AND " . BargainsService::STATUS . '=1';
		$onpage = isset ( $requestParams ['onpage'] )&& $requestParams ['onpage'] !=NULL ? $requestParams ['onpage'] : 5;
		if (isset ( $requestParams ['page'] )&& $requestParams ['page'] !=NULL ){ 
			$limit = ($requestParams ['page']-1)*$onpage . "," . $onpage;
		} else {
			$limit = "0,$onpage";
		}
		if (isset ( $requestParams [BargainsService::BARGAIN_NAME] )&& $requestParams [BargainsService::BARGAIN_NAME] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= BargainsService::BARGAINS . '.' . BargainsService::BARGAIN_NAME . " LIKE '%" . $requestParams [BargainsService::BARGAIN_NAME] . "%'";
		}
		if (isset ( $requestParams [BargainsService::FROM_PRICE] )&& $requestParams [BargainsService::FROM_PRICE] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= BargainsService::BARGAINS . '.' . BargainsService::PRICE . ' >= ' . $requestParams [BargainsService::FROM_PRICE];
		}
		if (isset ( $requestParams [BargainsService::UNTIL_PRICE] )&& $requestParams [BargainsService::UNTIL_PRICE] !=NULL ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= BargainsService::BARGAINS . '.' . BargainsService::PRICE . ' <= ' . $requestParams [BargainsService::UNTIL_PRICE];
		}
		if (isset ( $requestParams [BargainsService::COUNTRY] ) && $requestParams [BargainsService::COUNTRY]!='all' ){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= BargainsService::BARGAINS . '.' . BargainsService::COUNTRY . '=' . $requestParams [BargainsService::COUNTRY];
		}
		if (isset ( $requestParams [BargainsService::REGION] ) && $requestParams [BargainsService::REGION]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= BargainsService::BARGAINS . '.' . BargainsService::REGION . '=' . $requestParams [BargainsService::REGION];
		}
		if (isset ( $requestParams [BargainsService::CATEGORY_ID] ) && $requestParams [BargainsService::CATEGORY_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= BargainsService::BARGAINS . '.' . BargainsService::CATEGORY_ID . '=' . $requestParams [BargainsService::CATEGORY_ID];
		}
		if (isset ( $requestParams [BargainsService::SUBCATEGORY_ID] ) && $requestParams [BargainsService::SUBCATEGORY_ID]!='all'){ 
			$where .= $where!=NULL ? ' AND ' : NULL;
			$where .= BargainsService::BARGAINS . '.' . BargainsService::SUBCATEGORY_ID . '=' . $requestParams [BargainsService::SUBCATEGORY_ID];
		}
		$bargains = BargainsService::getBargains ($where, $limit);
		//print_r($bargains);
		if ($bargains!=NULL){
			foreach($bargains as $key => $value){
				if ($value[UploadsService::PATH]!=NULL){
					$part = explode('.',$value[UploadsService::PATH]);
					$bargains[$key][UploadsService::PATH] = $part[0] . '-thumbnail.' . $part[1];
				}
			}
		}
		isset ( $bargains ) ? $mvc->addObject ( BargainsService::BARGAINS, $bargains ) : null;
		
		$all_bargains = BargainsService::countBargains ($where);
		isset ( $bargains ) ? $mvc->addObject ( "count",  $all_bargains[BargainsService::BARGAINS] ) : null;
		
		$pages = $all_bargains[BargainsService::BARGAINS]/$onpage; 
		isset ( $orders ) ? $mvc->addObject ( "pages",  $pages ) : null;
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		if (isset ($requestParams[BargainsService::CATEGORY_ID])){
			$crntCategory = $requestParams[BargainsService::CATEGORY_ID]!='all' ? 
								$requestParams[BargainsService::CATEGORY_ID] : 
									$category[0][CategoryService::ID];  
			$subcategory = SubCategoryService::getSubcatByCat ($crntCategory);
			isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		}
		
		$subcategory2 = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory2 ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY . "2", $subcategory2 ) : null;
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
		return $mvc;
	}
	
	public function handleViewBargain($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		if(isset($requestParams[BargainsService::ID])){
			$where = BargainsService::HASH . "='" . $requestParams[BargainsService::ID] . "'";
			$bargain = BargainsService::getBargains($where);
			
			if (isset($requestParams['buy']) && $bargain[0][BargainsService::NUMBER]>0){
				$role = SessionService::getAttribute ( SessionService::ROLE );
				if ($role!=NULL){
					if ($role==UsersService::ROLE_UR){
						if ($requestParams['amount']!=0&&$requestParams['amount']<=$bargain[0][BargainsService::NUMBER]){
							for($i=1;$i<=$requestParams['amount'];$i++){
								BargainsService::buyBargain($bargain[0][BargainsService::ID], $id);
							}
							
							$where = BargainsService::HASH . "='" . $requestParams[BargainsService::ID] . "'";
							$bargain = BargainsService::getBargains($where);
							
							$subject = $bargain[0][BargainsService::BARGAIN_NAME];
							$sender_id = SessionService::getAttribute ( SessionService::USERS_ID );
							$user = SessionService::getAttribute ( SessionService::USERNAME );
							$text = $user . " bought ".$requestParams['amount']." item <a href=\'/view-bargain.html?id=" . $bargain[0][BargainsService::HASH] . "\'>" . $bargain[0][BargainsService::BARGAIN_NAME] . "<\/a>";
							$getter_id = $bargain[0][BargainsService::USER_ID];
							#prepare text for email 
							$plain = $mvc->getProperty ( 'newMessage' );
							MailService::sendMail($subject, $text, $sender_id, $getter_id, $plain);
							$mvc->addObject ( self::STATUS, "_i18n{You bought }".$requestParams['amount']. " _i18n{items} !" );
						} else {
							$mvc->addObject ( self::ERROR, "_i18n{You can't buy more then }". $bargain[0][BargainsService::NUMBER] . " _i18n{items}." );
						}
					} else {
						$mvc->addObject ( self::ERROR, "_i18n{Only standart user can buy this item, you can} <a href='/registration.html?role=1' title='_i18n{register}'>_i18n{register}</a> like tradesman." );
					}
				} else {
					$mvc->addObject ( self::ERROR, "_i18n{Please}, <a href='/login.html' title='_i18n{login}'>_i18n{login}</a> _i18n{if you alredy registred, or you can} <a href='/registration.html' title='_i18n{register}'>_i18n{register}</a>." );
				}
			}
			
			$bargain[0]['time_left'] = self::countTimeLeft($bargain[0]['until_date']);
			isset ( $bargain ) ? $mvc->addObject ( BargainsService::BARGAINS, $bargain[0] ) : null;
			
			$images = BargainsService::getBargainImgs ($bargain[0][BargainsService::ID]);
			foreach($images as $key => $value){
				$part = explode('.',$value[UploadsService::PATH]);
				$images[$key][UploadsService::PATH2] = $part[0] . '-thumbnail.' . $part[1];
			}
			isset ( $images ) ? $mvc->addObject ( UploadsService::PATH, $images ) : null;
			
			$where = BargainsService::BARGAINS . '.' . BargainsService::ID . '='. $bargain[0][BargainsService::ID];
			$boughtBargains = BargainsService::getBoughtBargain($where);
			$mvc->addObject ( BargainsService::BOUGHT_BARGAIN, $boughtBargains);
			if(isset($id)){
				$where = BargainsService::BARGAINS . '.' . BargainsService::ID . '='. $bargain[0][BargainsService::ID] . ' AND ' . 
						BargainsService::BOUGHT_BARGAIN . '.' . BargainsService::USER_ID . '=' . $id;
				$boughtByUser = BargainsService::getBoughtBargain($where);
				$mvc->addObject ( 'count', $boughtByUser);
			}
		}	
		return $mvc;
	}
	
	private function countTimeLeft($date_time_string){
		$currenttime = time();
	    $date_elements = explode('-',$date_time_string);
	    $newtime= mktime(null, null,null, $date_elements[1],$date_elements[2], $date_elements[0]);
	    
	    $days=floor(($newtime-time())/86400);
	    $hours=floor(($newtime-time())/3600-($days*24));
	    $mins=floor(($newtime-time())/60-($days*1440)-($hours*60));
	    $secs=floor(($newtime-time())-($days*86400)-($hours*3600)-($mins*60));
		return "$days _i18n{days} $hours _i18n{hours} $mins _i18n{mins} $secs _i18n{secs}";
	}


	private function breakword ($txt,$len,$delim='\s;,.!?:#') {
	    $txt = preg_replace_callback ("#(</?[a-z]+(?:>|\s[^>]*>)|[^<]+)#mi"
	                                  ,create_function('$a'
	                                                  ,'static $len = '.$len.';'
	                                                  .'$len1 = $len-1;'
	                                                  .'$delim = \''.str_replace("#","\\#",$delim).'\';'
	                                                  .'if ("<" == $a[0]{0}) return $a[0];'
	                                                  .'if ($len<=0) return "";'
	                                                  .'$res = preg_split("#(.{0,$len1}+(?=[$delim]))|(.{0,$len}[^$delim]*)#ms",$a[0],2,PREG_SPLIT_DELIM_CAPTURE);'
	                                                  .'if ($res[1]) { $len -= strlen($res[1])+1; $res = $res[1];}'
	                                                  .'else         { $len -= strlen($res[2]); $res = $res[2];}'
	                                                  .'$res = rtrim($res);/*preg_replace("#[$delim]+$#m","",$res);*/'
	                                                  .'return $res;')
	                                  ,$txt);
	     while (preg_match("#<([a-z]+)[^>]*>\s*</\\1>#mi",$txt)) {
	         $txt = preg_replace("#<([a-z]+)[^>]*>\s*</\\1>#mi","",$txt);
	     }
	     return $txt;
	}

	public static function createTeaserWord ($word){
		$word = substr($word, 0, 255);
		$word = strrev(strstr(strrev($word), ' '));
		return $word;
	}
	
	
	public function handleNewMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$location = $this->onSuccess( $actionParams );
		isset($requestParams['back']) ? $this->forwardActionRequest ( $location ) : NULL;
		if (isset($requestParams [MailService::RE]) || isset($requestParams [MailService::FWD])){
			$id = isset($requestParams [MailService::RE]) ? $requestParams [MailService::RE] : $requestParams [MailService::FWD];
			$mail = MailService::getMail($id);
			$replay = isset($requestParams [MailService::RE]) ? 1 : 0;
			$subject = $replay ? '_i18n{Re}: ' : '_i18n{Fwd}: ' ; 
			$subject .= $mail[MailService::SUBJECT];
			$date = $mail [MailService::CRDATE];
			$sender = $mail [MailService::SENDER];
			$getter = $mail [MailService::GETTER];
			$getter_id = $mail[MailService::GETTER_ID];
			$text = "<div><br/><br/><br/></div><div><font class='Apple-style-span'; color='#999999'>";
			$text .= '_i18n{On} ' . $date . ', ' . $sender . ' _i18n{wrote}:';
			$text .= "<div><br/></div>";
			$text .= $mail [MailService::TEXT];
			$text .= "</font></div>";
			$result = array(MailService::SUBJECT=>$subject,MailService::GETTER=>$sender,MailService::TEXT=>$text,MailService::RE=>$replay);
			$mvc->addObject ( self::RESULT, $result );
		}
		if (isset($requestParams [MailService::SEND])){
			$subject = htmlspecialchars($requestParams [MailService::SUBJECT], ENT_QUOTES);
			$text = htmlspecialchars($requestParams [MailService::TEXT], ENT_QUOTES);
			$sender_id = SessionService::getAttribute ( SessionService::USERS_ID );
			$getter = htmlspecialchars($requestParams [MailService::GETTER], ENT_QUOTES);
			$getter_id = UsersService::getUserIdByName($getter);
			if (isset($getter_id) && $getter_id!=NULL){
				#prepare text for email 
				$plain = $mvc->getProperty ( 'newMessage' );
				MailService::sendMail($subject, $text, $sender_id, $getter_id, $plain);
				$this->forwardActionRequest ( $location );
			} else {
				$error [] .= '_i18n{No such user.}';
				$mvc->addObject ( self::ERROR, $error );
				$result = array(MailService::SUBJECT=>$subject,MailService::GETTER=>$getter,MailService::TEXT=>$text);
				$mvc->addObject ( self::RESULT, $result );
			}
		}
		return $mvc;
	}
	
	public function handleViewMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		MailService::readMail ( $requestParams[MailService::ID] );
		
		$mail = MailService::getMail( $requestParams[MailService::ID] );
		isset ( $mail ) ? $mvc->addObject (self::RESULT, $mail ) : null;
		
		return $mvc;
	}
	
	public function handleMyMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		isset ( $requestParams [self::DEL] ) ? MailService::deleteMail ($requestParams [self::DEL]) : null;
		
		$limit = '0,5';
		
		#get inbox
		$inbox = MailService::getInbox( $id , $limit );
		isset ( $inbox ) ? $mvc->addObject ( MailService::INBOX, $inbox ) : null;
		
		#get outbox
		$outbox = MailService::getOutbox( $id , $limit );
		isset ( $outbox ) ? $mvc->addObject ( MailService::OUTBOX, $outbox ) : null;
		
		#get trash
		$trash = MailService::getTrash( $id , $limit );
		isset ( $trash ) ? $mvc->addObject ( MailService::TRASH, $trash ) : null;
		
		return $mvc;
	}
	
	public function handleInbox($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		
		#get inbox
		$inbox = MailService::getInbox( $id );
		isset ( $inbox ) ? $mvc->addObject ( MailService::INBOX, $inbox ) : null;
		
		return $mvc;
	}
	
	public function handleOutbox($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		
		#get outbox
		$outbox = MailService::getOutbox( $id );
		isset ( $outbox ) ? $mvc->addObject ( MailService::OUTBOX, $outbox ) : null;
		
		return $mvc;
	}
	
	public function handleTrash($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [self::DEL_ALL] ) ? 
			MailService::deleteMails ($requestParams ['itemSelect']) :
				null;
		
		#get trash
		$trash = MailService::getTrash( $id );
		isset ( $trash ) ? $mvc->addObject ( MailService::TRASH, $trash ) : null;
		
		return $mvc;
	}
	
	public function createDate($date){
		$date = explode('/', $date);
		$result = $date[2] . '-' . $date[1] . '-' . $date[0];
		return $result;
	}
	
}
?>