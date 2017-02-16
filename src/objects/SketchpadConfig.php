<?php namespace davestewart\sketchpad\objects;
use davestewart\sketchpad\objects\install\JSON;
use davestewart\sketchpad\objects\settings\Paths;

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
		 * The settings file
		 *
		 * @var JSON $settings
		 */
		public $settings;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		public function __construct()
		{
		    $paths              = app(Paths::class);
		    $settings           = new JSON($paths->storage('settings.json'));
            $this->settings     = $settings->src;

            if ($settings->exists())
            {
                // values
                $this->route    = $settings->get('config.route');
                $this->assets   = $settings->get('config.assets');
                $this->views    = $settings->get('config.views');

                // paths
                $paths          = $settings->get('config.paths');
                foreach($paths as $obj)
                {
                    if($obj['enabled'])
                    {
                        $this->paths[$obj['name']] = rtrim($obj['path'], '/') . '/';
                    }
                }

                // ensure route is bounded by slashes to prevent concatenation issue later
                $this->route    = '/' . trim($this->route, '/') . '/';
            }
		}

}