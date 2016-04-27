<?php namespace davestewart\sketchpad\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\sketchpad\objects\route
 */
class RouteReference
{
	public $route;

	public function __construct($route)
	{
		$this->route = $route;
	}
	
	public function getName()
	{
		$segments = explode('/', trim($this->route, '/'));
		return array_pop($segments);
	}

	public function getDepth()
	{
		$segments = explode('/', trim($this->route, '/'));
		return count($segments);
	}

}
