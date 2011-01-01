<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class FooterController extends ContentController {
	
	
	public function handleCarousel($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$result = BargainsService::getBargainCatalog(true);
		$mvc->addObject ( BargainsService::BARGAINS, $result );
		
		return $mvc;
	}
	
}

?>

