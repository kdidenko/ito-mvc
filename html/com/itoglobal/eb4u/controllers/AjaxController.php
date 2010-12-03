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
	
	
}
?>