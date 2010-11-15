<?php
class MessageService {
    // defines the default search path
    const DEFAULT_PATH = 'messages';
    // defines the default locale code
    const DEFAULT_LOCALE = 'en';
    // defines the default extension for dictionary file
    const EXT_MESSAGES = 'messages';
    // defines the extension for missing messages dictionary file
    const EXT_MISSING = 'missing';
    // working path property
    private $path;
    // working locale property
    private $locale;
    // working dictionary array
    private $dict = array();
    // the array of keys not found in dictionary
    private $missed = array();
    // the flag defines whether the missing keys should be saved
    private $saveOnExit = true;
    // the flag defines whether to sort keys when messages are updated
    private $sortOnUpdate = true;
    // the singletone instance
    private static $instance = null;
    /**
     * Default class constructor. Loads the dictionary file
     * on instance initialization.
     * @return $instance of MessageService
     */
    private function __construct () {
        $this->loadMessages();
    }

    /**
     * Returns the currently used locale
     * @return the $locale
     */
    public function getLocale () {
        return isset($this->locale) ? $this->locale: self::DEFAULT_LOCALE;
    }

	/**
	 * Returns the currently used search path
     * @return the $path
     */
    public function getPath () {
        return isset($this->path) ? $this->path : self::DEFAULT_PATH;
    }
	/**
     * @param $locale the $locale to set
     */
    public function setLocale ($locale) {
        $this->locale = $locale;
    }


	/**
	 * Set whether to sort keys when updating messages.
     * @param $sortOnUpdate the $sortOnUpdate to set
     */
    public function setSortOnUpdate ($sortOnUpdate = true) {
        $this->sortOnUpdate = $sortOnUpdate;
    }

    /**
     * Default class destructor. Removes the class instance and
     * saves $missed dictionary keys if $missed flag is set to true.
     * @return void
     */
    function __destruct() {
        if($this->saveOnExit && count($this->missed) > 0){
            // compose the filename
            $file = $this->getPath() . '/' . $this->getLocale() . '.' .self::EXT_MISSING;
            // try to find any saved data before overriding it with new one
            $out = $this->fromTextFile($file, false);
            // merge old and new messages
            $out = array_merge($this->missed, $out);
            // save merged array into file
            $this->saveMessages($out, $file, 'w+');
        }
    }
	/**
	 * Set whether the missing keys should be saved.
     * @param $saveOnExit the $saveOnExit to set
     */
    public function setSaveOnExit ($saveOnExit) {
        $this->saveOnExit = $saveOnExit;
    }
    /**
     * Gets the MessageService instance.
     * @return $instance of MessageService class
     */
    public static function getInstance () {
        self::$instance = ! self::$instance ? new MessageService() : self::$instance;
        return self::$instance;
    }
    /**
     * Loads the dictionary file for the locale specified.
     * Uses 'en-US' locale if no parameters are specified.
     * @param $locale the dictionary locale to be used
     * @param $path [otional] defines the dictionary search path
     * @return void
     */
    public function loadMessages ($locale = self::DEFAULT_LOCALE, $path = self::DEFAULT_PATH) {
        // compose the filename
        $file = "$path/$locale." . self::EXT_MESSAGES;
        if (file_exists($file)) {
            // update the working path and locale properties
            $this->path = $path;
            $this->locale = $locale;
            // read the dictionary
            $this->dict = $this->fromTextFile($file);
        }
    }

    private static function lineConvert($line){
    	$k = ''; $v = '';    	
        $line = rtrim($line, "\r\n");
        if(strpos($line, '=')){
        	list($k, $v) = explode('=', $line, 2);
        }		
        return array($k => $v);
    }

    private function fromTextFile($file, $sort = null){
        $sort = ($sort === null) ? $this->sortOnUpdate : $sort;
        $out = array();
        // read raw data
        $raw = file_exists($file) ? file($file) : $out;
        // convert each line into key => message pair
        if(count($raw) > 0){
            foreach ($raw as $key => $val){
                $out = array_merge($out, self::lineConvert($val));
                unset($raw[$key]);
            }
        }
        if($sort) { asort($out, SORT_STRING); }
        return $out;
    }
    /**
     * Writes the $messages dictionary into a $file. This method will
     * try to append new records in case file already exists. This
     * behaviour may be changed by setting another $mode parameter value.
     * @see http://www.php.net/manual/en/function.fopen.php
     * 		for full list of possible modes.
     * @param $messages dictionary array to save
     * @param $file filename to sava as dictionary
     * @param $mode [optional] specifies the file writing mode
     * 		default value: 'a' (append)
     * @return int bytes written
     */
    public function saveMessages($messages, $file, $mode='a'){
        $bytes = 0;
        if( $this->sortOnUpdate ) {
            asort($messages, SORT_STRING);
        }
        if(($h = fopen($file, $mode)) != false) {
            foreach($messages as $key => $value) {
                $record = $key . '=' . $value . PHP_EOL;
                $bytes += fwrite($h, $record, strlen($record));
            }
        }
        return $bytes;
    }
    /**
     * Get the message from current dictionary.
     * @param $key messsage key
     * @return $message string
     */
    public function getMessage ($key) {
        $result = '';
        if (! key_exists($key, $this->dict)) {
            $this->missed[$key] = $key;
            $result = $key;
        } else {
            $result = $this->dict[$key];
        }
        return $result;
    }
}
?>