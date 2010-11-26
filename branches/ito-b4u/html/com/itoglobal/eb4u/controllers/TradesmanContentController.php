<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class TradesmanContentController extends ContentController {
	
	const ERROR = 'error';
	const PSW_ERROR = 'psw_error';
	const STATUS = 'status';
	
	public function handleMyProfile($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );

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
							'3'=>UsersService::COMPANY,
							'4'=>UsersService::VAT,
							'5'=>UsersService::ADDRESS,
							'6'=>UsersService::ZIP,
							'7'=>UsersService::LOCATION,
							'8'=>UsersService::REGION,
							'9'=>UsersService::COUNTRY,
							'10'=>UsersService::PHONE,
							'11'=>UsersService::HOMEPAGE
							);
			$vals = array (
							'0'=>$requestParams [UsersService::FIRSTNAME], 
							'1'=>$requestParams [UsersService::LASTNAME],
							'2'=>$requestParams [UsersService::EMAIL],
							'3'=>$requestParams [UsersService::COMPANY],
							'4'=>$requestParams [UsersService::VAT],
							'5'=>$requestParams [UsersService::ADDRESS],
							'6'=>$requestParams [UsersService::ZIP],
							'7'=>$requestParams [UsersService::LOCATION],
							'8'=>$requestParams [UsersService::REGION],
							'9'=>$requestParams [UsersService::COUNTRY],
							'10'=>$requestParams [UsersService::PHONE],
							'11'=>$requestParams [UsersService::HOMEPAGE]
							);
			
			UsersService::updateFields ( $id, $fields, $vals );
			$mvc->addObject ( self::STATUS, 'successful' );
		}
		
		#get user info
		$result = UsersService::getUser ( $id );
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		return $mvc;
	}
	
	public function handleMyMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		isset ( $requestParams [MailService::DRAFTS] ) ? MailService::goDrafts ($requestParams [MailService::DRAFTS]) : null;
		isset ( $requestParams [MailService::TRASH] ) ? MailService::goTrash ($requestParams [MailService::TRASH]) : null;
		isset ( $requestParams [MailService::DEL] ) ? MailService::deleteMail ($requestParams [MailService::DEL]) : null;
		
		isset ($requestParams [MailService::SEND]) ?
			MailService::sendMail($requestParams [MailService::SUBJECT], $requestParams [MailService::TEXT], $requestParams [MailService::SENDER], $requestParams [MailService::GETTER]) :
				null;
		
		//echo "<h1>NEW: ";
		$new_mails = MailService::countNew($id);
		isset ( $new_mails ) ? $mvc->addObject ( MailService::NEW_MAILS, $new_mails ) : null;
		//echo $new_mails[MailService::NEW_MAILS];		
		//echo "</h1></br>";
				
		#get inbox
		$inbox = MailService::getInbox( $id );
		isset ( $inbox ) ? $mvc->addObject ( MailService::INBOX, $inbox ) : null;
		//echo "<h1>inbox</h1></br>";
		//print_r($inbox);
		
		#get outbox
		$outbox = MailService::getOutbox( $id );
		isset ( $outbox ) ? $mvc->addObject ( MailService::OUTBOX, $outbox ) : null;
		//echo "<h1>outbox</h1></br>";
		//print_r($outbox);
		
		#get drafts
		$drafts = MailService::getDrafts( $id );
		isset ( $drafts ) ? $mvc->addObject ( MailService::DRAFTS, $drafts ) : null;
		//echo "<h1>drafts</h1></br>";
		//print_r($drafts);
		
		#get trash
		$trash = MailService::getTrash( $id );
		isset ( $trash ) ? $mvc->addObject ( MailService::TRASH, $trash ) : null;
		//echo "<h1>trash</h1></br>";
		//print_r($trash);
		
		
		return $mvc;
	}
}
?>