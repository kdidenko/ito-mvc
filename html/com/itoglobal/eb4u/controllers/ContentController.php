<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
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
}
?>