<?php namespace davestewart\sketchpad\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\sketchpad\objects\route
 */
class ControllerReference extends RouteReference
{
	public $class;

	public $method;

	public $params;

	public $path;

	public function __construct($route, $path, $class = null)
	{
		parent::__construct('controller', $route);
		$this->path     = $path;
		$this->class    = $class;
		//$this->folder   = preg_replace('/[^\/]+$/', '', $path);
	}

}
