<?php namespace davestewart\doodle\objects;

/**
 * Class DoodleConfig
 *
 * @package davestewart\doodle\objects
 */
class DoodleConfig
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
			$config = config('doodle');
			foreach($config as $key => $value)
			{
				$this->$key = $value;
			}
		}


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS


}