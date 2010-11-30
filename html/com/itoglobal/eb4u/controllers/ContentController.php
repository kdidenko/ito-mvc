<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	const ERROR = 'error';
	
	const PSW_ERROR = 'psw_error';
	
	const IMAGE_ERROR = 'image_errors';
	
	const STATUS = 'status';
	
	const RESULT = 'result';
	/**
	 * @var string defines the user details constant
	 */
	const USER_DETAILS = 'USER';
	
/*	public static function createTeaser ($list){
		if (count($list)>0){
			foreach($list as $key => $value){
				$chr = strpos($value[SchoolService::DESCRIPTION], '</p>');
				$value[SchoolService::DESCRIPTION] = $chr != NULL ? 
					substr($value[SchoolService::DESCRIPTION],0,$chr) : 
						$value[SchoolService::DESCRIPTION];
				$value[SchoolService::DESCRIPTION] = substr($value[SchoolService::DESCRIPTION], 0, 255);
				$list[$key][SchoolService::DESCRIPTION] = strrev(strstr(strrev($value[SchoolService::DESCRIPTION]), ' '));
			}
		}
		return $list;
	}
	public static function createTeaserWord ($word){
		$word = substr($word, 0, 255);
		$word = strrev(strstr(strrev($word), ' '));
		return $word;
	}*/
	
	
	public function handleNewMail($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$location = $this->onSuccess( $actionParams );			
		isset($requestParams['back']) ? $this->forwardActionRequest ( $location ) : NULL;
		if (isset($requestParams [MailService::SEND])){
			$subject = $requestParams [MailService::SUBJECT];
			$text = $requestParams [MailService::TEXT];
			$sender_id = SessionService::getAttribute ( SessionService::USERS_ID );
			$getter = $requestParams [MailService::GETTER];
			$getter_id = UsersService::getUserIdByName($getter);
			if (isset($getter_id) && $getter_id!=NULL){
				MailService::sendMail($subject, $text, $sender_id, $getter_id);
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
	
}
?>