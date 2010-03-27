<?php
class ActionsMappingResolver  {

    const DEFAULT_MAPPING_FILE = 'actions-mapping.xml';

    const DEFAULT_CONTEXT_NAME = 'actions';

    const DEFAULT_MAPPING_SFX = 'mapping';

    private static $context = '';

    /**
     * @var array() mappings objects buffer.
     */
    private static $actionsMapping = array();

    /**
     * Service initialyzation method used for mapping XML processing.
     *
     * @param $mapping the MVC mapping filename.
     * @return void
     */
    public static function init ($mapping = self::DEFAULT_MAPPING_FILE, $context = '') {
        self::$context = $context;
        $xmlStr = file_get_contents($mapping);
        $xmlObj = new SimpleXMLElement($xmlStr);
        foreach ($xmlObj->action as $action) {
            self::$actionsMapping[(string) $action['name']] = $action;
        }
    }
	/**
     * @param $context the $context to set
     */
    public static function setContext ($context) {
        ActionsMappingResolver::$context = $context;
    }

    private static function wildcardToExp($wildcard){
            $exp = '@^' . str_replace(
                        array("\*", "\?", "\(", "\)", "\|"),
                        array('(.*)', '.', '(', ')', '|'),
                        preg_quote($wildcard)
                   ) . '$@is';
        return $exp;
    }

	/**
     * @return the $context
     */
    public static function getContext () {
        return ActionsMappingResolver::$context;
    }


    public static function getActionMapping ($uri) {
        $res = null;
        if (!key_exists($uri, self::$actionsMapping)){
            // try wilecard matching if no object were found
            $res = self::patternSearch($uri);
        } else {
            $res = self::$actionsMapping[$uri];
        }
        return $res;
    }

    private static function patternSearch($uri) {
        $keys = array_keys(self::$actionsMapping);
        $res = null; $mat = array();
        foreach($keys as $wldcrd){
            if (strpos($wldcrd, "*") || strpos($wldcrd, "?")){
                $exp = self::wildcardToExp($wldcrd);
                if(preg_match($exp,$uri,$mat)){
                    $res = self::$actionsMapping[$wldcrd];
                    //TODO: it is a quick solution to insert matches into the object. May crap xml tructutre!
                    $xml = $res->asXML();
                    for($i=1; $i < count($mat); $i++){
                        $xml = str_replace('$'.$i, $mat[$i], $xml);
                    }
                    $res = simplexml_load_string($xml);
                    $res['name'] = $uri;
                    return $res;
                }
            }

        }
        return $res;
    }
}
?>