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
		
		#get inbox
		//$inbox = self::createDate(MailService::getInbox( $id ));
		$inbox = MailService::getInbox( $id );
		//$inbox = self::createDate($inbox);
		isset ( $inbox ) ? $mvc->addObject ( MailService::INBOX, $inbox ) : null;
		
		#get outbox
		$outbox = MailService::getOutbox( $id );
		isset ( $outbox ) ? $mvc->addObject ( MailService::OUTBOX, $outbox ) : null;
		
		#get trash
		$trash = MailService::getTrash( $id );
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
	
}
?>