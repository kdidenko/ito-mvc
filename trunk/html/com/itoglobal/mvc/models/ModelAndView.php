<?php

class ModelAndView {
	
	/**
	 * @var string defines the request parameters constant
	 */
	const REQUEST_PARAMS = 'request_params';
	
	/**
	 * @var string defines the action properties constant
	 */
	const ACTION_PROPS = 'action_properties';	
	
	private $view = null;
	
	private $template = null;
	
	private $context = '';
	
	private $model = array ();
	/**
	 * @return the $template
	 */
	public function getTemplate() {
		return $this->template;
	}
	/**
	 * @param $template the $template to set
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}
	/**
	 * @return the $view
	 */
	public function getView() {
		return $this->view;
	}
	/**
	 * @param $context the $context to set
	 */
	public function setContext($context) {
		$this->context = $context;
	}
	
	/**
	 * @return the $context
	 */
	public function getContext() {
		return $this->context != '' ? $this->context . '/' : '';
	}
	
	/**
	 * @return the $model
	 */
	public function getModel() {
		return $this->model;
	}
	
	/**
	 * @param $view the $view to set
	 */
	public function setView($view) {
		$this->view = $view;
	}
	
	/**
	 * @param $model the $model to set
	 */
	public function setModel($model) {
		$this->model = $model;
	}
	
	/**
	 * @param $name the $name of the $object to add to $model
	 * @param $object the $object to add to $model
	 */
	public function addObject($name, $object) {
		$this->model [$name] = $object;
	}
	
	/**
	 * Returns the list of model object names
	 * @return array of model object keys 
	 */
	public function getKeyset() {
		return array_keys ( $this->model );
	}
	
	/**
	 * Verifies if object of such name already exists at the model
	 * @param string $name of the model object
	 */
	public function objectExists($name) {
		return array_key_exists ( $name, $this->model );
	}
	
	/**
	 * Retreives the object from the model by it's name
	 * @param $name the name of the object to get from model
	 * @return the object
	 */
	public function getObject($name) {
		return key_exists ( $name, $this->model ) ? $this->model [$name] : null;
	}
	
	/**
	 * Retreives the property from the model by it's name
	 * @param $name the name of the property to get from model
	 * @return the property
	 */
	public function getProperty($name) {
		$result = null;
		$res = $this->model [self::ACTION_PROPS];
		for($i = 0; $i < count ( $res ); $i ++) {
			$result = ($name == $res [$i] ['name']) ? $res [$i] ['value'] : $result;
		}
		return $result;
	}
	
	/**
	 * Retreives request parameter from the model by it's name
	 * @param $name the name of the request parameter to get from model
	 * @return the parameter
	 */	
	public function getParam($name) {
		$result = null;
		$res = $this->model [self::REQUEST_PARAMS];
		if (isset($res) && count($res)>0){
			$result = array_key_exists($name, $res) ?  $res[$name] : null; 
		}
		return $result;
	}	

}

?>