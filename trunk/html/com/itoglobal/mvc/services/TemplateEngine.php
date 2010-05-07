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
				include_once ( string ) $template ['path'];
		}
	}
	
	private static function doXSLT($template) {
		if (isset ( $template->input ) && self::TYPE_FILE == ( string ) $template->input ['type']) {
			echo XsltHandler::getInstance ()->transform ( ( string ) $template->input ['value'], ( string ) $template ['path'] );
		}
	}
	
	/**
	 * Load and populate the plain text template with values 
	 * @param the $filename of template
	 * @param the $vars of values
	 * @return string plain text content
	 */
	public static function doPlain($filename, $vars) {
		//TODO: implement the StorageService::getFileContent method
		$content = StorageService::getFileContent ( $filename );
		foreach ( $vars as $key => $value ) {
			$content = str_replace ( $key, $value, $content );
		}
		return $content;
	}
	
	/** 
	 * Wrapper method for including files from template 
	 * @param string the $filename to include
	 * @param ModelAndView the $mvc Object
	 */
	public static function inclusion($filename, $mvc = null) {
		include_once TEMPLATES_PATH . $filename;
	}
	
	public static function execute($action) {
		$rd = RequestDispatcher::getInstance ();
		TemplateEngine::run ( $rd->dispatchActionRequest ( $action ) );
	}
	
	public static function getView($view) {
		$rd = RequestDispatcher::getInstance ();
		TemplateEngine::run ( $rd->dispatchViewRequest ( $view ) );
	}

}
?>