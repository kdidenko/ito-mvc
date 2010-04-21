<?php


//TODO: modify RequestDispatcher - modify RequestDispatcher class for different types requests handling
//TODO: implement dispatchHttpRequest method

class RequestDispatcher {

	private static $instance = NULL; 
	
	public function dispatchHttpRequest($request){
				
	}
	
	public function dispatchActionRequest($action){
				
	}	
	
	private function __construct(){
	}
	
	public function getInstance(){
		return (self::$instance === NULL) ? self::$instance = new self() : self::$instance;
	}
}

?>