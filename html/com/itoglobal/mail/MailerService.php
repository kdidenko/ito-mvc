<?php

class MailerService {
	
	public static function replaceVars($email, $user, $firstname, $lastname, $path, $url) {
		$headers = 'From: SoundShare noreply@' . $_SERVER ['SERVER_NAME'];
		$subject = 'SoundShare support';
		$vars ['###FIRST_NAME###'] = $firstname;
		$vars ['###LAST_NAME###'] = $lastname;
		$vars ['###CONFIRMATION_URL###'] = $url;
		$vars ['###USERNAME###'] = $user;
		$message = TemplateEngine::doPlain ( $path, $vars );
		self::sendMail($message, $email, $subject, $headers);
	}
	
	
	public static function sendMail($message, $email, $subject, $headers){
		mail ( $email, $subject, $message, $headers );	
	}
}

?>