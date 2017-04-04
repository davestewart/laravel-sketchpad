<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\objects\file\File;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Class ControllerError
 */
class ControllerError extends File implements Arrayable, JsonSerializable
{
	public $path;
	public $label;
	public $error;

	public function __construct($path, $route, $error)
	{
		parent::__construct($path, $route);

		$info = pathinfo($path);
		$label = preg_replace('/^(.+)Controller$/', '$1', $info['filename']);
		$this->label = $label;
		$this->error = $error;
	}

	public function toArray()
	{
		$data =
		[
			'type'      => 'controller',
			'error'     => $this->error,
			'path'      => str_replace(base_path() . '/', '', $this->path),
			'folder'    => preg_replace('%[^/]+$%', '', $this->route),
			'route'     => $this->route,
			'label'     => $this->label,
			'methods'   => []
		];
		return $data;
	}

	function jsonSerialize()
	{
		return $this->toArray();
	}

}
