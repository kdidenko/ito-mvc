<?php

require_once 'com/itoglobal/sound/controllers/ContentController.php';

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
		
		$where = CompanyService::USER_ID . '=' . $id;
		$recommendations = CompanyService::getRating (null,$where);
		isset ( $recommendations ) ? $mvc->addObject ( CompanyService::COMPANY_FEEDBACK, $recommendations ) : null;
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
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
	
	public function handleMyRecommendations($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		
		$where = CompanyService::COMPANY_FEEDBACK . '.' . CompanyService::USER_ID . '=' . $id;
		$recommendations = CompanyService::getRecommendations ($where);
		isset ( $recommendations ) ? $mvc->addObject ( CompanyService::COMPANY_FEEDBACK, $recommendations ) : null;
		
		return $mvc;
	}
	
	public function handleWriteRecommendation($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute(SessionService::USERS_ID);
		
		if (isset($requestParams[CompanyService::ID])){
			if (isset($requestParams['send'])){
				$error = array();
				$error[] .= isset($requestParams[CompanyService::VOTE1])&&$requestParams[CompanyService::VOTE1]==NULL || isset($requestParams[CompanyService::VOTE1])&&$requestParams[CompanyService::VOTE1]<1 || isset($requestParams[CompanyService::VOTE1])&&$requestParams[CompanyService::VOTE1]>5 || !isset($requestParams[CompanyService::VOTE1]) ? 
					"Please, evaluate1 this company." : false;
				$error[] .= isset($requestParams[CompanyService::VOTE2])&&$requestParams[CompanyService::VOTE2]==NULL || isset($requestParams[CompanyService::VOTE2])&&$requestParams[CompanyService::VOTE2]<1 || isset($requestParams[CompanyService::VOTE2])&&$requestParams[CompanyService::VOTE2]>5 || !isset($requestParams[CompanyService::VOTE2]) ? 
					"Please, evaluate2 this company." : false;
				$error[] .= isset($requestParams[CompanyService::VOTE3])&&$requestParams[CompanyService::VOTE3]==NULL || isset($requestParams[CompanyService::VOTE3])&&$requestParams[CompanyService::VOTE3]<1 || isset($requestParams[CompanyService::VOTE3])&&$requestParams[CompanyService::VOTE3]>5 || !isset($requestParams[CompanyService::VOTE3]) ? 
					"Please, evaluate3 this company." : false;
				$error[] .= isset($requestParams[CompanyService::VOTE4])&&$requestParams[CompanyService::VOTE4]==NULL || isset($requestParams[CompanyService::VOTE4])&&$requestParams[CompanyService::VOTE4]<1 || isset($requestParams[CompanyService::VOTE4])&&$requestParams[CompanyService::VOTE4]>5 || !isset($requestParams[CompanyService::VOTE4]) ? 
					"Please, evaluate4 this company." : false;
				$error[] .= isset($requestParams[CompanyService::VOTE5])&&$requestParams[CompanyService::VOTE5]==NULL || isset($requestParams[CompanyService::VOTE5])&&$requestParams[CompanyService::VOTE5]<1 || isset($requestParams[CompanyService::VOTE5])&&$requestParams[CompanyService::VOTE5]>5 || !isset($requestParams[CompanyService::VOTE5]) ? 
					"Please, evaluate5 this company." : false;
				$error[] .= isset($requestParams[CompanyService::VOTE6])&&$requestParams[CompanyService::VOTE6]==NULL || isset($requestParams[CompanyService::VOTE6])&&$requestParams[CompanyService::VOTE6]<1 || isset($requestParams[CompanyService::VOTE6])&&$requestParams[CompanyService::VOTE6]>5 || !isset($requestParams[CompanyService::VOTE6]) ? 
					"Please, evaluate6 this company." : false;
				$error[] .= $requestParams[CompanyService::COMMENT]==NULL ? "_i18n{Please, write short comment.}" : false;
				$error = array_filter ( $error );
				if (count ( $error ) == 0) {
					exit;
					$vote=round(($requestParams[CompanyService::VOTE1]+$requestParams[CompanyService::VOTE2]+$requestParams[CompanyService::VOTE3]+$requestParams[CompanyService::VOTE4]+$requestParams[CompanyService::VOTE5]+$requestParams[CompanyService::VOTE6])/6, 2);
					$date = date("Y-m-d");
					$vals = array('0' => $requestParams[CompanyService::VOTE1]*20, 
									'1' => $requestParams[CompanyService::VOTE2]*20,
									'2' => $requestParams[CompanyService::VOTE3]*20,
									'3' => $requestParams[CompanyService::VOTE4]*20,
									'4' => $requestParams[CompanyService::VOTE5]*20,
									'5' => $requestParams[CompanyService::VOTE6]*20,
									'6'=>$vote,
									'7'=>$requestParams[CompanyService::COMMENT], 
									'8'=>$date, 
									'9'=>'1');
					$fields = array('0' => CompanyService::VOTE1, 
									'1' => CompanyService::VOTE2,
									'2' => CompanyService::VOTE3,
									'3' => CompanyService::VOTE4,
									'4' => CompanyService::VOTE5,
									'5' => CompanyService::VOTE6,
									'6' => CompanyService::VOTE,
									'7'=> CompanyService::COMMENT,
									'8' => CompanyService::DATE, 
									'9'=> CompanyService::DONE);
					CompanyService::postReview  ($requestParams[CompanyService::ID], $user_id, $fields, $vals);
					$mvc->addObject(ContentController::STATUS, "_i18n{Thank you for your response! You can go back to continue your work.}");
				} else {
					$mvc->addObject ( self::ERROR, $error );
				}
			}
			$where = CompanyService::COMPANY_FEEDBACK . '.' . CompanyService::ID . '=' . $requestParams[CompanyService::ID];
			$orders = CompanyService::getFeedback ($where);
			isset ( $orders ) ? $mvc->addObject ( OrdersService::ORDERS, $orders ) : null;
		}
		
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