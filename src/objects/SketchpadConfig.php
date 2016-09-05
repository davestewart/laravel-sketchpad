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
		 * The base route to all Sketchpad calls
		 *
		 * @example /sketchpad/
		 *
		 * @var string $route
		 */
		public $route;

		/**
		 * An array of paths to controller folders
		 *
		 * @var string[] $path
		 */
		public $paths;

		/**
		 * The public-relative path to the assets folder
		 *
		 * @var string $assets
		 */
		public $assets;

		/**
		 * An optional path to a views folder
		 *
		 * @var string[] $path
		 */
		public $views;

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
			if($config)
			{
				foreach($config as $key => $value)
				{
					$this->$key = $value;
				}

				// update trailing slash on paths
				$this->paths = array_map(function ($path)
				{
					return rtrim($path, '/') . '/';
				}, $this->paths);

				// ensure route is bounded by slashes to prevent concatenation issue later
				$this->route    = '/' . trim($this->route, '/') . '/';
			}
		}

}