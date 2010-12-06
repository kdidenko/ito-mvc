<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	
	const CRNT_PLAN = 'crnt_plan';
	
	const OTHER_PLAN = 'other_plan';
	
	/**
	 * @param unknown_type $actionParams
	 * @param unknown_type $requestParams
	 * @return $modelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		if (isset ($id) && $id != null){
			$new_mails = MailService::countNew($id);
			$mvc->addObject ( MailService::NEW_MAILS, $new_mails[MailService::NEW_MAILS]);
		}
		
		//TODO: put navigation handling here
		return $mvc;		
	}
	
	public function handleSidebarBlock($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		
		$plan = SessionService::getAttribute(SessionService::PLAN_ID);
		$plan = PlanService::getPlan($plan);
		$other_plans = PlanService::getOtherPlans($plan[PlanService::ID]);
		//print_r($other_plans);
		$mvc->addObject ( self::OTHER_PLAN, $other_plans);
		$mvc->addObject ( self::CRNT_PLAN, $plan[PlanService::PLAN_NAME]);
		return $mvc;
	}
}

?>