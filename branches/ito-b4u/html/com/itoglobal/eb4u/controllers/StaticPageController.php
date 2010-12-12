<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class StaticPageController extends SecureActionControllerImpl {

	const TITLE = 'title';
	
	const BODY = 'body';
	
	public function handleActionRequest($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		
		$view = $mvc->getView();
		switch ($view){
			case 'useragreement':
				$id = 24;
		}
		$block = StaticBlockService::getBlockInfo($id);
		if(isset ( $block )){
			$mvc->addObject ( self::TITLE, $block[StaticBlockService::BLOCK_TITLE] );
			$mvc->addObject ( self::BODY, htmlspecialchars_decode($block[StaticBlockService::BLOCK_DESC]) );
		}
				
		return $mvc;
	}
	
	
}
?>