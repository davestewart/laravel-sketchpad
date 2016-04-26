<?php namespace davestewart\doodle\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\doodle\objects\route
 */
class FolderReference extends RouteReference
{
	public $path;

	public function __construct($path)
	{
		$this->path = $path;
	}

}
