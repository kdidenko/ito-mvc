<?php
/**
 * The Interface definition for basic Action Controlling functionality.
 */
interface BaseActionController {
	
	/**
	 * @final constant: name of the default action handling method
	 * used in case of no other methods were defined.
	 */
	const MVC_DEFAULT_METHOD = 'handleActionRequest';
	
	/* @final constant: specifies the successful action processing state */
	const MVC_ON_SUCCESS = 'onsuccess';
	
	/* @final constant: specifies the failure on action processing state */
	const MVC_ON_FAILURE = 'onfailure';
	
	/* @final constant: specifies action processing aborted state */
	const MVC_ON_ABORT = 'onabort';
	
	/**
	 * Default action request handling method.
	 * It is automatically executed after controller is
	 * assigned for resolved action handling.
	 *
	 * @param $actionParams action definition parameters
	 * @param $requestParams list of action request parameters
	 * @return $modelAndView instance of ModelAndView Object
	 */
	public function handleActionRequest($actionParams, $requestParams);
	
	/**
	 * Forwards the action processing request to a specified location
	 * by sending location headers to the client. This method if used for
	 * cases when processing state is unexpected for the action controller
	 * or when the forward condition was defined in current action mapping.
	 *
	 * @param $location
	 * @return unknown_type
	 */
	public function forwardActionRequest($location);
	
	/**
	 * Optionally, resolves the location to forward the request
	 * in case of successful action processing.
	 *
	 * @param $actionParams
	 * @return $location
	 */
	public function onSuccess($actionParams);
	
	/**
	 * Optionally, resolves the location to forward the request
	 * in case of failure during the action processing.
	 *
	 * @param $actionParams
	 * @return $location
	 */
	public function onFailure($actionParams);
	
	/**
	 * Optionally, resolves the location to forward the request
	 * in case of action processing was aborted.
	 *
	 * @param $actionParams
	 * @return $location
	 */
	public function onAbort($actionParams);

}
?>