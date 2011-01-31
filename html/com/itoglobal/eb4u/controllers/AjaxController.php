<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class AjaxController extends SecureActionControllerImpl {

	const RESULT = 'result';
	
	public function handleGetSubcategory($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$id = $requestParams[SubCategoryService::ID];
		
		$result = SubCategoryService::getSubcatByCat($id);
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		
		return $mvc;
	}
	
	public function handleGetComment($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$id = $requestParams[UsersService::ID];
		if(isset($id)){
			$onpage = ON_PAGE;
			if (isset ( $requestParams ['comment_page'] )&& $requestParams ['comment_page'] !=NULL ){ 
				$limit = ($requestParams ['comment_page']-1)*$onpage . "," . $onpage;
			} else {
				$limit = "0,$onpage";
			}
			$where = CompanyService::COMPANY_ID . '=' . $id . ' AND ' . CompanyService::DONE . '=1';
			$feedbacks = CompanyService::getFeedback ($where,$limit);
			isset ( $feedbacks ) ? $mvc->addObject ( CompanyService::COMPANY_FEEDBACK, $feedbacks ) : null;
			
			$all_feedback = CompanyService::countFeedback ($where);
			isset ( $feedbacks ) ? $mvc->addObject ( "count",  $all_feedback[CompanyService::COMPANY_FEEDBACK] ) : null;
			
			$pages = $all_feedback[CompanyService::COMPANY_FEEDBACK]/$onpage; 
			isset ( $feedbacks ) ? $mvc->addObject ( "pages",  $pages ) : null;
		}
		return $mvc;
	}
	
	public function handleFeedback($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams['send'])){
			$error = array();
			$error[] .= ValidationService::checkEmail($requestParams['email']);
			$error[] .= !isset($requestParams['subject'])||$requestParams['subject']==NULL ? "_i18n{Please, write mail subject.}" : false;
			$error[] .= !isset($requestParams['body'])||$requestParams['body']==NULL ? "_i18n{Please, write your problem in message body.}" : false;
			$error = array_filter ( $error );
			if (count ( $error ) == 0) {
				$message = $requestParams['body'];
				$email = SUPPORT;
				$subject = "Support eBids4u : " .$requestParams['subject'];
				$headers = 'From: eBids4U noreply@' . $_SERVER ['SERVER_NAME'];
				MailerService::sendMail($message, $email, $subject, $headers);
				$mvc->addObject ( 'success', true );
			} else {
				$mvc->addObject ( 'error', $error );
			}
		}
		return $mvc;
	}
	
}
?>