<?php namespace davestewart\sketchpad\objects\route;

/**
 * ControllerError Reference object
 */
class ControllerErrorReference extends RouteReference
{
	public $error;

	/**
	 * ControllerReference constructor
	 *
	 * @param   string  $route
	 * @param   string  $path
	 * @param   string  $error
	 */
	public function __construct($route, $path, $error = null)
	{
		parent::__construct('controller', $route, $path);
		$this->error = $error;
	}

}
