<?php namespace davestewart\sketchpad\objects;

/**
 * Class SketchpadConfig
 *
 * @package davestewart\sketchpad\objects
 */
class SketchpadConfig
{

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		public $route;
		public $path;
		public $namespace;
		public $assets;
		public $theme;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		public function __construct()
		{
			$config = config('sketchpad');
			foreach($config as $key => $value)
			{
				$this->$key = $value;
			}
		}


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS


}