<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	
	
	
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
	
	public function handlePlanBlock($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		
		$plan = SessionService::getAttribute(SessionService::PLAN_ID);
		$plan = PlanService::getPlan($plan);
		$other_plans = PlanService::getOtherPlans($plan[PlanService::ID]);
		$mvc->addObject ( PlanService::OTHER_PLAN, $other_plans);
		$mvc->addObject ( PlanService::CRNT_PLAN, $plan[PlanService::PLAN_NAME]);
		return $mvc;
	}
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		$result = BargainsService::getBargainCatalog();
		$mvc->addObject ( BargainsService::BARGAINS, $result );
		
		return $mvc;
	}
	
	public function handleCompanies($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
	
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;
		
		return $mvc;
	}
}

?>