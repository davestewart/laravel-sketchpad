<?php namespace davestewart\sketchpad\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\sketchpad\objects\route
 */
class RouteReference
{

    /**
     * The absolute path to the controller file
     * @var
     */
    public $abspath;

    /**
     * The relative path to the controller file
     * @var
     */
    public $path;

    public $route;

	public $type;

	public function __construct($type, $route, $path)
	{
		$this->type     = $type;
		$this->route    = $route;
        $this->abspath  = $path;
        $this->path     = str_replace(base_path() . '/', '', $path);

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
