<?php namespace davestewart\sketchpad\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\sketchpad\objects\route
 */
abstract class RouteReference
{

    /**
     * The relative path to the controller file
     * @var
     */
    public $path;

    public $route;

	public $type;

	public function __construct($type, $route, $abspath)
	{
		$this->type     = $type;
		$this->route    = $route;
        $this->path     = str_replace(base_path() . '/', '', $abspath);

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

	public function exists()
	{
		return file_exists(base_path($this->path));
	}

}
