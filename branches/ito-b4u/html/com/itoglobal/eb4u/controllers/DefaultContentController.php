<?php

require_once 'com/itoglobal/eb4u/controllers/ContentController.php';

class DefaultContentController extends ContentController {
	
	
	
	public function handleHome($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$category = CategoryService::getCategories ();
		isset ( $category ) ? $mvc->addObject ( CategoryService::CATEGORY, $category ) : null;

		$subcategory = SubCategoryService::getSubcatByCat ($category[0][CategoryService::ID]);
		isset ( $subcategory ) ? $mvc->addObject ( SubCategoryService::SUBCATEGORY, $subcategory ) : null;

		return $mvc;
	}
	
}

?>

