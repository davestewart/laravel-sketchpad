<?php namespace davestewart\doodle\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\doodle\objects\route
 */
class PathReference extends RouteReference
{
	public $path;

	public function __construct($path)
	{
		$this->path = $path;
	}

}
