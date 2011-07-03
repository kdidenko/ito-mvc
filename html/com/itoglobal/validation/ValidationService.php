<?php

class ValidationService {
	
	public static function alphaNumeric($field){
		return ctype_alnum($field);		
	}
	
	public static function checkEmail($field){
		$result = !preg_match ( '/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $field ) ?
			'_i18n{Wrong email. Please enter a correct email}' :
				false;
		return $result;
	}
	
	public static function checkPassword($password){
		$result = strlen ( $password ) < 6 ? 
			'_i18n{The password you provided must have at least 6 characters.}' : 
				false;
		return $result;
	}
	
	public static  function checkConfirmPassword($password, $confirm_password){
		$result = $password != $confirm_password ? 
			'_i18n{Confirm Password does not match the password.}' : 
				false;
		return $result;
	}
	
	public static function checkAvatar($file) {
		$result = false;
		if (isset($file['tmp_name'])){
			$size = getimagesize ( $file ['tmp_name'] );
			$result = $size [0] <= 80 && $size [1] <= 80 ?
						false :
							'_i18n{Allowed image size 80x80.}';
		}
		return $result;
	}
	
}

?>