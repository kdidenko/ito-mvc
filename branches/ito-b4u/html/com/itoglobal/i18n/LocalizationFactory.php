<?php
class LocalizationFactory {

    private static $config;

    private static $instance;

    public function getAvailableLocales(){
    }

    public function getConfigXml(){
        return $this->config->asXML();
    }

    public function getConfigObj(){
        return $this->config;
    }

    private function __construct(){
        $file = MessageService::DEFAULT_PATH . '/locales.xml';
        $this->config = simplexml_load_file($file);
    }

    public static function getInstance(){
        self::$instance = self::$instance == null ? new LocalizationFactory() : self::$instance;
        return self::$instance;
    }
	


}
?>