<?php namespace davestewart\sketchpad\objects\scanners;

use App;
use davestewart\sketchpad\objects\reflection\Controller;
use davestewart\sketchpad\objects\route\ControllerReference;
use davestewart\sketchpad\objects\route\FolderReference;
use davestewart\sketchpad\traits\GetterTrait;
use Route;
use Session;

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
		public function __construct($path, $route = '/sketchpad/')
		{
			// parameters
			$this->path         = $path;
			$this->route        = '/' . trim($route, '/') . '/';

			// properties
			$this->routes       = [];
			$this->controllers  = [];
		}

		public static function make($path, $route)
		{
			return new self($path, $route = null);
		}


	// -----------------------------------------------------------------------------------------------------------------
	// MAKING METHODS

		/**
		 * Make a controller instance from an absolute path
		 *
		 * The method works out the correct route and passes it in
		 *
		 * @param   string      $abspath        The absolute path to the controller
		 * @return  Controller
		 */
		public function makeController($abspath)
		{
			$relpath        = substr($abspath, strlen($this->path));
			$route          = strtolower($this->route . preg_replace('/Controller\.php$/', '', $relpath)) . '/';
			return new Controller($abspath, $route);
		}


	// -----------------------------------------------------------------------------------------------------------------
	// SCANNING METHODS

		public function start()
		{
			$this->scan();
			$this->save();
			return $this;
		}

		public function getRoutes()
		{
			// existing routes
			if($this->routes)
			{
				return $this->routes;
			}

			// saved routes
			$routes = $this->load()->routes;
			if($routes)
			{
				return $routes;
			}

			// scan routes
			$this->start();
			return $this->routes;
		}

		public function save()
		{
			Session::put('sketchpad.routes', $this->routes);
			return $this;
		}

		public function load()
		{
			$this->routes = Session::get('sketchpad.routes');
			return $this;
		}


	// -----------------------------------------------------------------------------------------------------------------
	// PROTECTED SCANNING METHODS

		/**
		 * Finds all controllers and folders Recursive path processing function
		 *
		 * Sets controllers and folders elements as they are found
		 *
		 * @param   string  $path   The sketchpad controllers path-relative path to the folder, i.e. "foo/bar/"
		 */
		protected function scan($path = '')
		{
			// variables
			$root               = $this->folderize($this->path . $path);
			$files              = array_diff(scandir($root), ['.', '..']);

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
					$this->addController($abspath);
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
			$route          = $this->route . $path;
			$ref            = new FolderReference($route, $this->path . $path);
			$this->addRoute($route, $ref);
		}

		/**
		 * Adds a controller to the internal routes array
		 *
		 * @param          $abspath
		 */
		protected function addController($abspath)
		{
			$controller     = $this->makeController($abspath);
			$ref            = new ControllerReference($controller->route, $controller->path, $controller->classpath);

			// set route
			$this->controllers[] = $controller;
			$this->addRoute($controller->route, $ref);
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
