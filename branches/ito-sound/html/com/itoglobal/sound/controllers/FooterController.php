<?php

require_once 'com/itoglobal/sound/controllers/ContentController.php';

class FooterController extends ContentController {
	
	
	public function handleCarousel($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$result = BargainsService::getBargainCatalog(true);
		$mvc->addObject ( BargainsService::BARGAINS, $result );
		
		return $mvc;
	}
	
}

?>

