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
		 * The base route for controllers
		 *
		 * @var string
		 */
		public $route;

		/**
		 * An array of 'route' => RouteReference instances
		 *
		 * These are saved to the session and are used to quickly re-scan controllers later
		 *
		 * @var FolderReference[]
		 */
		public $routes;

		/**
		 * An array of Controller instances
		 *
		 * These are used to build the controller JSON array
		 *
		 * @var FolderReference[]
		 */
		public $controllers;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		/**
		 * Router constructor.
		 *
		 * Sets up the Router with the values it needs determine or match routes
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
			// add folder
			$this->addFolder($path);

			// get elements
			$root       = AbstractScanner::folderize($this->path . $path);
			$els        = array_diff(scandir($root), ['.', '..']);

			// split elements
			$files      = [];
			$folders    = [];
			foreach ($els as $el)
			{
				is_dir($root . $el)
					? array_push($folders, $el)
					: array_push($files, $el);
			}

			// files
			$controllers = [];
			foreach ($files as $file)
			{
				$abspath = $root . $file;
				if($this->isController($abspath))
				{
					$controller = $this->addController($abspath, $path);
					if ($controller)
					{
						// bit of a hack, but it works... using order + name to affect order
						$order = $this->getOrder($controller);
						$order = str_pad($order === 0 ? '999999' : $order, 6, 0, STR_PAD_LEFT);
						$name = $controller->classname;
						$controllers["$order:$name"] = $controller;
					}
				}

			}

			// sort controllers
			ksort($controllers);

			// add controllers
			$this->controllers = array_merge($this->controllers, array_values($controllers));

			// folders
			foreach ($folders as $folder)
			{
				$relpath = $path . $folder;
				$this->scan($relpath . '/');
			}

			$orders = array_map(function ($c) { return $c->classpath; }, $this->controllers);
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
		 * @param   string       $abspath
		 * @param   string       $route
		 * @return  Controller|\davestewart\sketchpad\objects\reflection\ControllerError
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
				// controller isn't abstract, has methods, isn't private
				if ($instance->isValid())
				{
					$ref = $instance->getReference();
					$this->addRoute($route, $ref);
					return $instance;
				}
			}

			else
			{
				$ref = $instance->getReference();
				$this->addRoute($route, $ref);
				return $instance;
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


	// -----------------------------------------------------------------------------------------------------------------
	// HELPERS

		/**
		 * Helper function to order controllers
		 *
		 * @param Controller $controller
		 * @return int
		 */
		protected function getOrder ($controller)
		{
			$order = 0;
			if ($controller instanceof Controller)
			{
				$order = (int) $controller->comment->getTag('order');
			}
			return $order;
		}

}
