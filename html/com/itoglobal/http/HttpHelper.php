<?php
//TODO: HttpHelper class implementation requires refactoring!
class HttpHelper {

    const REQ_METHOD = 'REQUEST_METHOD';

    const REQ_PROTOCOL = 'SERVER_PROTOCOL';

    const REQ_HOST = 'HTTP_HOST';

    const REQ_URI = 'REQUEST_URI';

    const REQ_QUERY = 'QUERY_STRING';

    const REQ_AGENT = 'HTTP_USER_AGENT';

    const REQ_LANG = 'HTTP_ACCEPT_LANGUAGE';

    const SRV_NAME = 'SERVER_NAME';

    const SRV_ADDR = 'SERVER_ADDR';

    const SRV_PORT = 'SERVER_PORT';

    const MVC_MAPNG = 'MAPPING';

    const DEFAULT_DOCUMENT_NAME = 'index.html';
    
    const DOC_ROOT = 'DOCUMENT_ROOT';
    
    const SCRIPT_NAME = 'SCRIPT_FILENAME';

    private static $contextName = '';

    private static $actName = '/index.html';

    private static $reqOpts = array();

    public static function init ($env) {
        // retreive existing request params from environment vars array
        if ($env != null && is_array($env) && count($env)) {
            /* request protocol properties block begin */
            $str = self::getValOrNull($env, self::REQ_PROTOCOL);
            if ($str != null) {
                // default pattern - NAME/VERSION (e.g: HTTP/1.1)
                list ($n, $v) = explode('/', $str);
                if ($n && $n != '' && $v && $v != null && is_float((floatval($v)))) {
                    self::$reqOpts[self::REQ_PROTOCOL] = strtolower($n) . '://';
                }
            }
            
            $str = self::getValOrNull($env, self::REQ_HOST);
            if ($str != null) {
                self::$reqOpts[self::REQ_HOST] = $str;
            }
            $str = self::getValOrNull($env, self::SRV_NAME);
            if ($str != null) {
                self::$reqOpts[self::SRV_NAME] = $str;
            }
            $str = self::getValOrNull($env, self::SRV_ADDR);
            if ($str != null) {
                self::$reqOpts[self::SRV_ADDR] = $str;
            }
            $str = self::getValOrNull($env, self::SRV_PORT);
            if ($str != null) {
                self::$reqOpts[self::SRV_PORT] = (int) $str;
            }
            
            /* request protocol properties block end */
            self::parseReqUrl($env);
        }
    }

    private static function parseReqUrl ($env) {
            // check for existing params for complicated requests
    	    $req_uri = self::getValOrNull($env, self::REQ_URI);
    	    // remove context path if application is not in document root
    	    $req_uri = self::trimContextPath($req_uri, $env);
    	    $req_qri = self::getValOrNull($env, self::REQ_QUERY);
    	    
    	    
    	    if ($req_qri != null) {
                self::$reqOpts[self::REQ_QUERY] = $req_qri;
                $req_uri = $req_uri != null ? str_replace($req_qri, '', $req_uri) : null;
            }

      	    if ($req_uri != null) {
      	        $req_uri = rtrim($req_uri, "?");
                self::$reqOpts[self::REQ_URI] = $req_uri;
            }

            $mapping = self::getValOrNull($env, self::MVC_MAPNG);
            self::$reqOpts[self::MVC_MAPNG] = $mapping ? $mapping : ActionsMappingResolver::DEFAULT_MAPPING_FILE;

            self::parseActionParams();
    }
    
    /**
     * Removes useless context path if application is not in a document root
     */
    private static function trimContextPath($req, $env){
    	$path = str_replace($env[self::DOC_ROOT], '', $env[self::SCRIPT_NAME]);
    	$path = str_replace(DEFAULT_SCRIPT, '', $path); 
		return  str_replace($path, '', $req);   	
    }
    

    private static function parseActionParams(){
        // get the mepping config name
        $mapping = self::getOpt(self::MVC_MAPNG);
        if($mapping != ActionsMappingResolver::DEFAULT_MAPPING_FILE){
            list($mappName, $suffix) = explode('-', $mapping);
            // compose full URL
            $url = self::getRequestUrl();
            // get mapping name and action parts
            if(($p = strpos($url, $mappName)) && $p > 0 && $suffix != ''){
                self::$contextName = $mappName;
                self::$actName = substr($url, $p + strlen($mappName));
            }
        } else {
            self::$actName = self::getOpt(self::REQ_URI);
        }
    }

    public static function getActionName () {
        return (self::$actName=='' || self::$actName=='/') ? '/'. self::DEFAULT_DOCUMENT_NAME : self::$actName;
    }

    public static function getContextName () {
        return self::$contextName;
    }

    public static function getRequestUrl () {
        $str = self::getOpt(self::REQ_PROTOCOL);
        $res = $str ? $str : '';
        $str = self::getOpt(self::REQ_HOST);
        if(!$str) {
            $str = self::getOpt(self::SRV_NAME);
            if(!$str) {
                $str = self::getOpt(self::SRV_ADDR);
                if(!$str) { $str = 'domain.tld'; }
            }
        }
        $res .= $str;
        $str = self::getOpt(self::SRV_PORT);
        $res .= ($str && 80 != (int) $str) ? ":$str" : '';
        $str = self::getOpt(self::REQ_URI);
        $res .= $str ? $str : '/' . self::DEFAULT_DOCUMENT_NAME;
        return $res;
    }

    private static function getOpt($name){
        return key_exists($name, self::$reqOpts) ? self::$reqOpts[$name] : false;
    }

    private static function getValOrNull ($obj = null, $prop = null) {
        $res = null;
        //TODO: complete this method and move it somewhere to utils!
        if ($obj && isset($obj) && $obj != null) {
            if ($prop && isset($prop) && $prop != null) {
                if (is_array($obj)) {
                    if (key_exists($prop, $obj)) {
                        $res = $obj[$prop];
                    }
                    // other cases..
                }
                // other types..
            } else {
                $res = $obj;
            }
        }
        return $res;
    }

    public static function getMappingParam(){
        return self::getOpt(self::MVC_MAPNG);
    }

}
?>