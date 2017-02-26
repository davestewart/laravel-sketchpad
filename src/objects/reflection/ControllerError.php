<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\objects\file\File;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Class ControllerError
 */
class ControllerError extends File implements Arrayable, JsonSerializable
{
	public $error;

	public function __construct($path, $route, $error)
	{
		parent::__construct($path, $route);
		$this->error = $error;
	}

	public function toArray()
	{
		$data =
		[
			'error'     => $this->error,
			'abspath'   => $this->path,
			'route'     => $this->route,
		];
		return $data;
	}

	function jsonSerialize()
	{
		return $this->toArray();
	}

}
