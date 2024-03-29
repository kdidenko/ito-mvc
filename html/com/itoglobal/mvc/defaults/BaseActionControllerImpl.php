<?php
require_once 'BaseActionController.php';

class BaseActionControllerImpl implements BaseActionController {
	
	/**
	 * Retreives the location parameter for a specified action processing
	 * state condition using action configuration model object.
	 *
	 * @param $actionParams
	 * @param $condition
	 * @return string
	 */
	public function getLocationOnCondition($actionParams, $condition) {
		$result = null;
		if ($actionParams && ($forwards = $actionParams->forwards)) {
			foreach ( $forwards->target as $opt ) {
				if ($opt ['condition'] && $condition == ( string ) $opt ['condition']) {
					$result = ( string ) $opt ['location'];
					break;
				}
			}
		}
		return $result;
	}
	
	/**
	 * Base actions handling method implementation. Qualifies the
	 * minimum required for Model View Controlling. May be used for plain
	 * pageflow logic handling without any need for extending.
	 *
	 * @see BaseActionController->handleActionRequest($actionParams, $requestParams)
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		// create MVC model instance
		$modelAndView = new ModelAndView ();
		// define the view id
		$modelAndView->setView ( ( string ) $actionParams ['id'] );
		// add action mapping configuration defined properties
		$props = null;
		foreach ( $actionParams->property as $key => $value ) {
			$props [] = array ('name' => ( string ) $value ['name'], 'value' => ( string ) $value ['value'] );
		}
		$modelAndView->addObject ( 'action_properties', $props );
		$modelAndView->addObject ( 'action_forwards', $actionParams->forwards );
		// add received request parameters
		$modelAndView->addObject ( 'request_params', $requestParams );
		// set the template property
		$modelAndView->setTemplate ( $actionParams->template );
		// return create MVC model object
		return $modelAndView;
	}
	
	/**
	 * Implements the action request forwarding defined by
	 * BaseActionController interface.
	 *
	 * @param $location the location to forward the action request
	 * @return void
	 */
	public function forwardActionRequest($location) {
		@ob_clean();
		header ( "Location: $location" );
		exit ();
	}
	
	/**
	 * Basic implementation of onSuccess method defined by
	 * com.itoglobal.mvc.defaults.BaseActionController interface.
	 *
	 * @see BaseActionController->onSuccess($actionParams)
	 */
	public function onSuccess($actionParams) {
		return self::getLocationOnCondition ( $actionParams, self::MVC_ON_SUCCESS );
	}
	
	/**
	 * Basic implementation of onFailure method defined by
	 * com.itoglobal.mvc.defaults.BaseActionController interface.
	 *
	 * @see BaseActionController->onFailure($actionParams)
	 */
	public function onFailure($actionParams) {
		return self::getLocationOnCondition ( $actionParams, self::MVC_ON_FAILURE );
	}
	
	/**
	 * Basic implementation of onAbort method defined by
	 * com.itoglobal.mvc.defaults.BaseActionController interface.
	 *
	 * @see BaseActionController->onAbort($actionParams)
	 */
	public function onAbort($actionParams) {
		return self::getLocationOnCondition ( $actionParams, self::MVC_ON_ABORT );
	}

	public function setNoCashe() {
		header("Pragma: no-cache");
		header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	}
	
}
?>