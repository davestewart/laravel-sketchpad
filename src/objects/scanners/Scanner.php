<?php namespace davestewart\sketchpad\objects\scanners;

use davestewart\sketchpad\objects\reflection\Controller;
use davestewart\sketchpad\objects\route\ControllerReference;
use davestewart\sketchpad\objects\route\ControllerErrorReference;
use davestewart\sketchpad\objects\route\FolderReference;
use davestewart\sketchpad\traits\GetterTrait;


/**
 * Router
 *
 * Responsible for:
 *
 * - determining the possible routes => Controllers from the sketchpad/ folder downwards
 * - matching any called routes to said controllers
 * - creating any wildcard routes if required
 */
class Scanner extends AbstractScanner
{
	
	use GetterTrait;

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * An array of 'route' => RouteReference instances, representing all found
		 * folders / controllers from the sketchpad/ controller folder down
		 *
		 * @var FolderReference[]
		 */
		public $routes;

		/**
		 * An array of Controller instances, representing all found
		 * folders / controllers from the sketchpad/ controller folder down
		 *
		 * @var FolderReference[]
		 */
		public $controllers;

		/**
		 * The base route for sketchpad/ controllers
		 *
		 * @var string
		 */
		public $route;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		/**
		 * Router constructor.
		 *
		 * Sets up the Router with the values it needs determine or match routes
		 *
		 * Parameters are all from the Sketchpad config file
		 *
		 * @param   string   $path
		 * @param   string   $route         the base route for sketchpad routes
		 */
		public function __construct($path, $route = '')
		{
			// parameters
			$this->path         = $path;
			$this->route        = trim($route, '/') . '/';

			// properties
			$this->routes       = [];
			$this->controllers  = [];
		}


	// -----------------------------------------------------------------------------------------------------------------
	// SCANNING METHODS

		public function start()
		{
			$this->scan();
			return $this;
		}


	// -----------------------------------------------------------------------------------------------------------------
	// PROTECTED SCANNING METHODS

		/**
		 * Recursively finds all controllers and folders
		 *
		 * Sets controllers and folders elements as they are found
		 *
		 * @param   string  $path   The sketchpad controllers path-relative path to the folder, i.e. "foo/bar/"
		 */
		protected function scan($path = '')
		{
			// variables
			$root               = AbstractScanner::folderize($this->path . $path);
			$files              = array_diff(scandir($root), ['.', '..']);

			//pd($this->route, $root, $path, $files);
			// folders
			$this->addFolder($path);

			// loop
			foreach ($files as $file)
			{
				// variables
				$abspath    = $root . $file;
				$relpath    = $path . $file;

				// parse
				if(is_dir($abspath))
				{
					$this->scan($relpath . '/');
				}
				else if($this->isController($abspath))
				{
					$this->addController($abspath, $path);
				}
			}
		}

		/**
		 * Adds a folder to the internal routes array
		 *
		 * @param   string  $path   The controller-root relative path to the folder
		 */
		protected function addFolder($path)
		{
			$route          = rtrim($this->route . $path, '/');
			$ref            = new FolderReference($route, $this->path . $path);
			$this->addRoute($route, $ref);
		}

		/**
		 * Adds a controller to the internal routes array
		 *
		 * @param          $abspath
		 */
		protected function addController($abspath, $route)
		{
			// variables
			$name           = pathinfo($abspath, PATHINFO_FILENAME);
			$segment        = preg_replace('/Controller$/', '', $name);
			$route          = strtolower($this->route . $route . $segment);

			// objects
			$instance       = Controller::fromPath($abspath, $route);

			// add
			if($instance instanceof Controller)
			{
				$ref                    = new ControllerReference($route, $instance->path, $instance->classpath);
				$this->controllers[]    = $instance;
				$this->addRoute($route, $ref);
			}

			else
			{
				$ref        = new ControllerErrorReference($route, $instance->path, $instance->error);
				$this->addRoute($route, $ref);
			}
		}

		/**
		 * Adds a new RouteReference obejct
		 *
		 * @param   string  $route  The URI route to be registered, i.e. "foo/bar/"
		 * @param   mixed   $ref    A PathReference instance
		 */
		protected function addRoute($route, $ref)
		{
			//$ref->route = $route;
			$this->routes[$route] = $ref;
		}

}
