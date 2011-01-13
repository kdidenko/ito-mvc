<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class UserContentController extends ContentController {
	
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

		$where = BargainsService::BOUGHT_BARGAIN . '.' . BargainsService::USER_ID . '=' . $id;
		$boughtBargains = BargainsService::getBoughtBargain($where);
		isset ( $boughtBargains ) ? $mvc->addObject ( BargainsService::BARGAINS, $boughtBargains ) : null;
		
		$where = OrdersService::OWNER . '=' . $id;
		$orders = OrdersService::getOrders ($where);
		isset ( $orders ) ? $mvc->addObject ( OrdersService::ORDERS, $orders ) : null;
		
		return $mvc;
	}
	
	public function handleMyBargains($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		
		$where = BargainsService::BOUGHT_BARGAIN . '.' . BargainsService::USER_ID . '=' . $id;
		$boughtBargains = BargainsService::getBoughtBargain($where);
		isset ( $boughtBargains ) ? $mvc->addObject ( BargainsService::BARGAINS, $boughtBargains ) : null;
		
		return $mvc;
	}
	
	public function handleMyOrder($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		
		$where = OrdersService::OWNER . '=' . $id;
		$orders = OrdersService::getOrders ($where);
		isset ( $orders ) ? $mvc->addObject ( OrdersService::ORDERS, $orders ) : null;
		
		return $mvc;
	}
	
	public function handleNewOrder($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if(isset($requestParams['save'])){
			$error = array();
			$files = $_FILES ['file'];
			foreach ($requestParams as $key => $value){
				$error[] .= $value==NULL ? true : false;
			}
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {

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
				
				//send mails
				
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
	
	/*
	private static function getUserRole(){
		#prepeare value for sql query 
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = UsersService::ROLE;
		$from = UsersService::USERS;		
		$where = UsersService::ID . "= '" . $id . "'";
		#get user role 
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0][UsersService::ROLE] : null;		
		return $result;
	}
	*/
}

?>