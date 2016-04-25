<?php namespace davestewart\doodle\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\doodle\objects\route
 */
class RouteReference
{
	public $route;

	public function __construct($route)
	{
		$this->route = $route;
	}

}
