<?php
require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

/**
 * Registration Controller
 * 	- implement user ragistration functionality
 *  - implement "forgot password" functionality
 * @author ITO-Global
 */
class RegistrationController extends SecureActionControllerImpl {
	
	/** 
	 * Validate input data and sending mail for confirmation ragistration
	 * @param array $actionParams
	 * @param array $requestParams
	 * @return ModelAndView
	 */
	public function registration($actionParams, $requestParams) {
		# calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset ( $requestParams ['submit'] )) {
			#server-side validation
			$error = UsersService::validation ( $requestParams );
			if (count ( $error ) == 0) {
				#Insert new users to DB
				$fields = UsersService::USERNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . 
							UsersService::CRDATE . ', ' . UsersService::VALIDATION . ', ' . UsersService::ROLE;
				$hash = md5 ( rand ( 1, 9999 ) );
				$role = $requestParams[UsersService::ROLE]==2 ? 'TR' : 'UR';
				$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::EMAIL] . "','" . 
							md5 ( $requestParams [UsersService::PASSWORD] ) . "','" . gmdate ( "Y-m-d H:i:s" ) . "','" . 
							$hash . "','" . $role . "'";
				$into = UsersService::USERS;
				$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
				#get user id 
				$id = $result;
				#prepare text for email 
				$plain = $mvc->getProperty ( 'template' );
				$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/confirm-registration.html?id=' . $id . '&validation_id=' . $hash;
				#call method for sending mail
				MailerService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], null, null, $plain, $url );
				$mvc->addObject ( UsersService::ON_SUCCESS, '_i18n{Your registration successful. Please check your email to confirm your account.}' );
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}		

		return $mvc;
	}

	/**
	 * Confirmation user registration
	 * @param array $actionParams
	 * @param array $requestParams
	 * @return ModelAndView
	 */
	public function confirmRegistration($actionParams, $requestParams) {
		# calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$id = $requestParams [UsersService::ID];
		$hash = $requestParams [UsersService::VALIDATION];	
		#get user data
		$result = UsersService::getUser($id);
		#cheking hash
		if (isset ( $result [UsersService::VALIDATION] ) && $result [UsersService::VALIDATION] == $hash) {
			$mvc->addObject ( UsersService::ROLE, $result[UsersService::ROLE] );
			if (isset ( $requestParams ['submit'] ) && $requestParams [UsersService::ROLE]=='UR') {
				$error = array();
				/*
				 * user fields
				 * address	zip	location region	country	phone homepage newsletter bank bank_code acoount_number
				*/
				$error [] .= $requestParams [UsersService::ADDRESS] ? false : true;
				$error [] .= $requestParams [UsersService::LOCATION] ? false : true;
				$error [] .= $requestParams [UsersService::REGION] ? false : true;
				$error [] .= $requestParams [UsersService::COUNTRY] ? false : true;
				$error [] .= $requestParams [UsersService::PHONE] ? false : true;
				$error  = array_filter ( $error );
				if (count ( $error ) == 0) {
					$this->createNewUser($requestParams, $result);
					$message = '_i18n{Your registration complete. Now you can} <a href="/login.html">_i18n{login.}</a>';
					//$mvc->addObject ( UsersService::ERROR, $message );
					$mvc->addObject ( UsersService::ON_SUCCESS, $message );
				} else {
					$mvc->addObject ( UsersService::ERROR, $error );
				}
			}
			if (isset ( $requestParams ['submit'] ) && $requestParams [UsersService::ROLE]=='TR') {
				$error = array();
				/*
				 * user fields
				 * address	zip	location region	country	phone homepage newsletter bank bank_code acoount_number
				*/
				$error [] .= $requestParams [UsersService::BANK] ? false : "_i18n{Please, enter bank info.}";
				$error [] .= $requestParams [UsersService::ACCOUNT_NUMBER] ? false : "_i18n{Please, enter account number.}";
				$error [] .= $requestParams [UsersService::PAYMENT] ? false : "_i18n{Please, choose payment method.}";
				$error  = array_filter ( $error );
				if (count ( $error ) == 0) {
					//$this->createNewUser($requestParams, $result);
					
					#creating directories for user
					StorageService::createDirectory ( StorageService::USERS_FOLDER . $result [UsersService::USERNAME] );
					StorageService::createDirectory ( StorageService::USERS_FOLDER . $result [UsersService::USERNAME] . StorageService::USER_PROFILE );
					$path = StorageService::USERS_FOLDER . $result [UsersService::USERNAME] . StorageService::USER_PROFILE . StorageService::USER_AVATAR;
					copy ( StorageService::DEF_USER_AVATAR, $path );
					
					# setting the query variables
					$fields = array ( '0' => UsersService::ENABLED, '1' => UsersService::AVATAR);
					$fields[] .= $requestParams[UsersService::BANK] ? UsersService::BANK : false;
					$fields[] .= $requestParams[UsersService::ACCOUNT_NUMBER] ? UsersService::ACCOUNT_NUMBER : false;
					$fields[] .= $requestParams[UsersService::PAYMENT] ? UsersService::PAYMENT : false;
					$fields[] .= isset($requestParams[UsersService::NEWSLETTER]) && $requestParams[UsersService::NEWSLETTER]!=NULL ? 'newsletter' : false;
					$vals = array ('0' => '1', '1' => $path);
					$vals[] .= $requestParams[UsersService::BANK] ? $requestParams[UsersService::BANK] : false;
					$vals[] .= $requestParams[UsersService::ACCOUNT_NUMBER] ? $requestParams[UsersService::ACCOUNT_NUMBER] : false;
					$vals[] .= $requestParams[UsersService::PAYMENT] ? $requestParams[UsersService::PAYMENT] : false;
					$vals[] .= isset($requestParams[UsersService::NEWSLETTER])&&$requestParams[UsersService::NEWSLETTER]!=NULL ? $requestParams[UsersService::NEWSLETTER] : false;
					$vals  = array_filter ( $vals );
					$fields  = array_filter ( $fields );
						
					#update user information
					UsersService::updateFields($requestParams[UsersService::ID], $fields, $vals );
					
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
						} 
					}
					
					$message = '_i18n{Your registration complete. Now you can} <a href="/login.html">_i18n{login.}</a>';
					//$mvc->addObject ( UsersService::ERROR, $message );
					$mvc->addObject ( UsersService::ON_SUCCESS, $message );
				} else {
					$mvc->addObject ( UsersService::ERROR, $error );
				}
			}
			
		} else {
			$location = $this->onFailure ( $actionParams );
			$this->forwardActionRequest ( $location );
		}
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubCategories ();
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		
		$countries = RegionService::getRegions ();
		isset ( $countries ) ? $mvc->addObject ( RegionService::REGIONS, $countries ) : null;
		
		$regions = CountryService::getCountries ();
		isset ( $regions ) ? $mvc->addObject ( CountryService::COUNTRY, $regions ) : null;
		
		$reminds_regions = RemindService::getRemindsRegionsByUser ($id);
		isset ( $reminds_regions ) ? $mvc->addObject ( RemindService::REGION_ID, $reminds_regions ) : null;
		
		$plans = PlanService::getPlans();
		isset ( $plans ) ? $mvc->addObject ( PlanService::PLAN, $plans ) : null;
		
		return $mvc;
	}
	
	private function createNewUser($requestParams, $result){
		#creating directories for user
		StorageService::createDirectory ( StorageService::USERS_FOLDER . $result [UsersService::USERNAME] );
		StorageService::createDirectory ( StorageService::USERS_FOLDER . $result [UsersService::USERNAME] . StorageService::USER_PROFILE );
		$path = StorageService::USERS_FOLDER . $result [UsersService::USERNAME] . StorageService::USER_PROFILE . StorageService::USER_AVATAR;
		copy ( StorageService::DEF_USER_AVATAR, $path );
		
		# setting the query variables
		$fields = array ( '0' => UsersService::ENABLED, '1' => UsersService::AVATAR);
		$fields[] .= $requestParams[UsersService::ADDRESS] ? 'address' : false;
		$fields[] .= $requestParams[UsersService::ZIP] ? 'zip' : false;
		$fields[] .= $requestParams[UsersService::LOCATION] ? 'location' : false;
		$fields[] .= $requestParams[UsersService::REGION] ? 'region' : false;
		$fields[] .= $requestParams[UsersService::COUNTRY] ? 'country' : false;
		$fields[] .= $requestParams[UsersService::PHONE] ? 'phone' : false;
		$fields[] .= $requestParams[UsersService::HOMEPAGE] ? 'homepage' : false;
		$fields[] .= $requestParams[UsersService::NEWSLETTER] ? 'newsletter' : false;
		$fields[] .= $requestParams[UsersService::BANK] ? 'bank' : false;
		//$fields[] .= $requestParams[UsersService::BANK_CODE] ? 'bank_code' : false;
		$fields[] .= $requestParams[UsersService::ACCOUNT_NUMBER] ? 'account_number' : false;
		$fields[] .= isset($requestParams[UsersService::COMPANY]) && $requestParams[UsersService::COMPANY] !=NULL ? 'company' : false;
		$fields[] .= isset($requestParams[UsersService::SEND_JOB]) && $requestParams[UsersService::SEND_JOB]!=NULL ? 'send_job' : false;
		$vals = array ('0' => '1', '1' => $path);
		$vals[] .= $requestParams[UsersService::ADDRESS] ? $requestParams[UsersService::ADDRESS] : false;
		$vals[] .= $requestParams[UsersService::ZIP] ? $requestParams[UsersService::ZIP] : false;
		$vals[] .= $requestParams[UsersService::LOCATION] ? $requestParams[UsersService::LOCATION] : false;
		$vals[] .= $requestParams[UsersService::REGION] ? $requestParams[UsersService::REGION] : false;
		$vals[] .= $requestParams[UsersService::COUNTRY] ? $requestParams[UsersService::COUNTRY] : false;
		$vals[] .= $requestParams[UsersService::PHONE] ? $requestParams[UsersService::PHONE] : false;
		$vals[] .= $requestParams[UsersService::HOMEPAGE] ? $requestParams[UsersService::HOMEPAGE] : false;
		$vals[] .= $requestParams[UsersService::NEWSLETTER] ? $requestParams[UsersService::NEWSLETTER] : false;
		$vals[] .= $requestParams[UsersService::BANK] ? $requestParams[UsersService::BANK] : false;
		//$vals[] .= $requestParams[UsersService::BANK_CODE] ? $requestParams[UsersService::BANK_CODE] : false;
		$vals[] .= $requestParams[UsersService::ACCOUNT_NUMBER] ? $requestParams[UsersService::ACCOUNT_NUMBER] : false;
		$vals[] .= isset($requestParams[UsersService::COMPANY])&&$requestParams[UsersService::COMPANY]!=NULL ? $requestParams[UsersService::COMPANY] : false;
		$vals[] .= isset($requestParams[UsersService::SEND_JOB])&&$requestParams[UsersService::COMPANY]!=NULL ? $requestParams[UsersService::SEND_JOB] : false;
		$vals  = array_filter ( $vals );
		$fields  = array_filter ( $fields );
		
		#update user information
		UsersService::updateFields($requestParams[UsersService::ID], $fields, $vals );
	}

	/**
	 * Cheking e-mail and send mail with url for reset password
	 * @param array $actionParams
	 * @param array $requestParams
	 * @return ModelAndView
	 */
	public function resetPassword($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		#checking, if user enter email
		if (isset ( $requestParams [UsersService::EMAIL] )) {
			#get user data
			$where = UsersService::EMAIL . " = '" . $requestParams [UsersService::EMAIL] . "'";
			$result = UsersService::getUsersList($where);
			#checking, if exist such email, and if user entered email same like in DB
			if (isset ( $result [0] [UsersService::EMAIL]) && $result [0] [UsersService::EMAIL] != NULL ) {
				#creating new hash for password reset and save him
				$hash = md5 ( rand ( 1, 9999 ) );
				$fields = array('0' => UsersService::VALIDATION);
				$vals = array('0' => $hash);
				$id = $result[0][UsersService::ID];
				UsersService::updateFields($id,$fields,$vals);
				#preparing url for mail
				$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?id=' . $id . '&validation_id=' . $hash;
				#sending mail
				MailerService::replaceVars ( $requestParams [UsersService::EMAIL], $result [0] [UsersService::USERNAME], $result [0] [UsersService::FIRSTNAME], $result [0] [UsersService::LASTNAME], $actionParams->property ['value'], $url );
				$mvc->addObject ( UsersService::ON_SUCCESS, '_i18n{Please, check your e-mail for continue.}' );	
			} else {
				$mvc->addObject ( UsersService::ERROR, '_i18n{E-mail address not found. Please, try again.}' );
			}
		}
		return $mvc;
	}
	
	/**
	 * Checking hash and creating new password
	 * @param array $actionParams
	 * @param array $requestParams
	 * @return ModelAndView
	 */
	public function newPassword($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );

		$mvc->addObject ( UsersService::ID, $requestParams [UsersService::ID] );
		$mvc->addObject ( UsersService::VALIDATION, $requestParams [UsersService::VALIDATION] );
		
		if ( isset ( $requestParams [UsersService::PASSWORD] )) {
			# get user data from DB
			$result = UsersService::getUser($requestParams[UsersService::ID]);
			if (isset ( $result [UsersService::VALIDATION] ) && $result [UsersService::VALIDATION] == $requestParams [UsersService::VALIDATION]) {
				#password validation
				$error = array ();
				$error [] .= UsersService::checkPassword ( $requestParams [UsersService::PASSWORD] );
				$error [] .= UsersService::checkConfirmPassword ( $requestParams [UsersService::PASSWORD], $requestParams [UsersService::CONFIRM] );
				$error = array_filter ( $error );
				if (count ( $error ) == 0) {
					#update password
					$fields = array('0' => UsersService::PASSWORD);
					$password = md5 ( $requestParams [UsersService::PASSWORD]);
					$vals = array('0' => $password);
					$id = $requestParams[UsersService::ID];
					UsersService::updateFields($id,$fields,$vals);
					$mvc->addObject ( UsersService::ON_SUCCESS, '_i18n{Your password was successful changed. Please,} <a href="/login.html">_i18n{login}</a> _i18n{whith new password.}' );
				} else {
					$mvc->addObject ( UsersService::ERROR, $error );
				}
			} else {
				$mvc->addObject ( UsersService::ERROR, '_i18n{Url is incorrect. Please, try again to} <a href="/forgot-password.html">_i18n{change password.}</a>' );
			}
		}
		return $mvc;
	}
}
?>