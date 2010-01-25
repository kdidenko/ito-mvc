<?php
/**
 *  XsltHandler singelton implements most common
 *  XML/XSL related functionality
 *
 *	@version 1.0
 */

class XsltHandler {
    // The XSLT processin element
    private $xslt;
    // The DOM handler required for most of xml/xsl processing functionality
    private $dom;
    // The instance itself
    private static $instance = null;

    /**
     * The private constructor. Use getInstance() instead!
     * @return unknown_type
     */
    private function __construct(){
        $this->dom = new DOMDocument();
        $this->xslt = new XSLTProcessor();
    }

	/**
	 * @return the $instance XsltHandler onject instance
     */
    public static function getInstance () {
        self::$instance = self::$instance ? self::$instance : new XsltHandler();
        return self::$instance;
    }


    /** Transforms SimpleXmlObject into html string
     * 	using XSLTProcessor and DOMDocument classes
     *  xsl stylesheet is searched by $xmlObj['id'] attribute.
     *  //TODO: provide a normal for resolving xsl's location.
     *
     * @param $xmlObj instance of SimpleXmlObject
     * @return html string
     */
    public function XmlObjAsHtml($xmlObj, $view = null) {
        $result = '';
        $view = $view == null ? (string) $xmlObj['id']  : $view;
        $xslFile = XSLT_PATH . $view . '.xsl';
        if(file_exists($xslFile)){
            // transform xsl file content into DOM object
            $this->dom->load($xslFile);
            // import xsl DOM to processor
            $this->xslt->importStyleSheet($this->dom);
            // load xml source into DOM
            $this->dom->loadXML($xmlObj->asXML());
            // execute XSLT
            $result = $this->xslt->transformToXML($this->dom);
        }
        return $result;
    }

}



?>