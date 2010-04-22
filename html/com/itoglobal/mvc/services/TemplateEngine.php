<?php
class TemplateEngine {
	
	const TYPE_HTML = 'HTML';
	
	const TYPE_XSLT = 'XSLT';
	
	const TYPE_FILE = 'FILE';
	
	public static function run($mvc) {
		$template = $mvc->getTemplate ();
		switch (( string ) $template ['type']) {
			case self::TYPE_XSLT :
				self::doXSLT ( $template );
				break;
			default :
				define ( 'TEMPLATE_PATH', ( string ) $template ['path'] );
				include_once TEMPLATE_PATH;
		}
	}
	
	private static function doXSLT($template) {
		if (isset ( $template->input ) && self::TYPE_FILE == ( string ) $template->input ['type']) {
			echo XsltHandler::getInstance ()->transform ( ( string ) $template->input ['value'], ( string ) $template ['path'] );
		}
	}
	
	/** 
	 * Wrapper method for including files from template 
	 * @param string the $filename to include
	 * @param ModelAndView the $mvc Object
	 */
	public static function inclusion($filename, $mvc = null) {
		include_once $filename;
	}
	
	public static function execute($action) {
		$rd = RequestDispatcher::getInstance ();
		TemplateEngine::run ( $rd->execute ( $action ) );
	}

}
?>