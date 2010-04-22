<?php

class ModelAndView {

	private $view = null;
	
	private $template = null;

	private $context = '';

	private $model = array();
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
    public function setContext ($context) {
        $this->context = $context;
    }

	/**
     * @return the $context
     */
    public function getContext () {
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
		$this->model[$name] = $object;
	}

	/**
	 * @param $name the $name of the $object to get from $model
	 * @return the $object
	 */
	public function getObject($name) {
		return key_exists($name, $this->model) ? $this->model[$name] : null;
	}


}

?>