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

		/**
		 * the base route to all Sketchpad calls
		 *
		 * @example /sketchpad/
		 *
		 * @var string $route
		 */
		public $route;

		/**
		 * The absolute path to the Sketchpad controllers folder
		 *
		 * @var string $path
		 */
		public $path;

		/**
		 * The base namespace for the Sketchpad controllers folder
		 *
		 * @var string $namespace
		 */
		public $namespace;

		/**
		 * The public-relative path to the assets folder
		 *
		 * @var string $assets
		 */
		public $assets;

		/**
		 * An optional array of middleware
		 *
		 * @var array $middleware
		 */
		public $middleware;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		public function __construct()
		{
			$config = config('sketchpad');
			foreach($config as $key => $value)
			{
				$this->$key = $value;
			}

			// ensure route is bounded by slashes to prevent concatenation issue later
			$this->route    = '/' . trim($this->route, '/') . '/';

			// convert the relative path to an absolute one
			$this->path     = base_path($this->path);
		}


}