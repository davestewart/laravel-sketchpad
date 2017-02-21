<?php namespace davestewart\sketchpad\config;

use davestewart\sketchpad\objects\install\JSON;
use davestewart\sketchpad\config\SketchpadSettings;

/**
 * Class SketchpadConfig
 *
 * @package davestewart\sketchpad\objects
 */
class SketchpadConfig
{

	// -----------------------------------------------------------------------------------------------------------------
	// properties

		/**
		 * The base route to all Sketchpad calls
		 *
		 * @var string $route
		 */
		public $route   = '/sketchpad/';

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
		 * The settings file as a JSON class
		 *
		 * @var SketchpadSettings $settings
		 */
		public $settings;


	// -----------------------------------------------------------------------------------------------------------------
	// instantiation

		public function __construct()
		{
		    $this->settings     = new SketchpadSettings();
			$this->load();
		}

	// -----------------------------------------------------------------------------------------------------------------
	// methods

		public function load()
		{
			if ($this->settings->exists())
			{
				$settings       = $this->settings;

				// values
				$this->route    = $settings->get('config.route');
				$this->assets   = $settings->get('config.assets');
				$this->views    = $settings->get('config.views');
				$paths          = $settings->get('config.paths');

				// ensure route is bounded by slashes to prevent concatenation issue later
				$this->route    = '/' . trim($this->route, '/') . '/';

				// paths
				foreach($paths as $obj)
				{
					if($obj['enabled'])
					{
						$this->paths[$obj['name']] = rtrim($obj['path'], '/') . '/';
					}
				}
			}

		}

}