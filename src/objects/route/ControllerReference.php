<?php namespace davestewart\sketchpad\objects\route;

/**
 * Controller Reference object
 *
 * Proxy for a full reflection controller object
 *
 * Used in 2 places:
 *
 *  1.  When scanning all the folders at the start.
 *
 *      The references are saved to the session so no need to re-scan later.
 *      At this point, only the path, class and route are saved
 *
 *  2.  When a method is called from the app
 *
 *      At this point, method and params are populated, and within Sketchpad
 *      to determine how to call the actual Controller method via App::call()
 */
class ControllerReference extends RouteReference
{
	/**
	 * The fully-qualified class path
	 * @var string
	 */
	public $class;

	/**
	 * A string method for the called method (only populated in phase 2)
	 * @var string
	 */
	public $method;

	/**
	 * An array of strings for the called parameters (only populated in phase 2)
	 * @var string[]
	 */
	public $params;

	/**
	 * ControllerReference constructor
	 *
	 * @param   string  $route
	 * @param   string  $path
	 * @param   string  $class
	 */
	public function __construct($route, $path, $class = null)
	{
		parent::__construct('controller', $route, $path);
		$this->class    = $class;
	}

}
