<?php namespace davestewart\doodle\objects\route;

/**
 * Class PathReference
 *
 * @package davestewart\doodle\objects\route
 */
class ControllerReference extends PathReference
{
	public $class;

	public $method;

	public $params;

	public function __construct($path, $class = null)
	{
		parent::__construct($path);
		$this->class = $class;
	}

}
