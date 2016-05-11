<?php namespace davestewart\sketchpad\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\sketchpad\objects\route
 */
class FolderReference extends RouteReference
{
	public $path;

	public function __construct($route, $path)
	{
		parent::__construct('folder', $route);
		$this->route    = $route;
		$this->path     = $path;
	}

}
