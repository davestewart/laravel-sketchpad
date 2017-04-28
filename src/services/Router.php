<?php namespace davestewart\sketchpad\services;

use davestewart\sketchpad\objects\reflection\Controller;
use davestewart\sketchpad\objects\reflection\ControllerError;
use davestewart\sketchpad\objects\route\CallReference;
use davestewart\sketchpad\objects\route\ControllerErrorReference;
use davestewart\sketchpad\objects\route\ControllerReference;
use davestewart\sketchpad\objects\route\FolderReference;
use davestewart\sketchpad\objects\route\RouteReference;
use davestewart\sketchpad\objects\scanners\AbstractScanner;
use davestewart\sketchpad\objects\scanners\Scanner;
use davestewart\sketchpad\traits\GetterTrait;
use Session;
/**
 * Router
 *
 * Responsible for:
 *
 * - determining the possible routes => Controllers from the sketchpad/ folder downwards
 * - matching any called routes to said controllers
 * - creating any wildcard routes if required
 *
 * @property Scanner $scanner
 */
class Router
{

	use GetterTrait;

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * @var string[]
		 */
		protected $paths;
	
		/**
         * A cache of all routes so
         *
		 * @var RouteReference[]
		 */
		protected $routes;
	
		/**
         * An array of full controller properties to pass to the front end
         *
		 * @var Controller[]
		 */
		protected $controllers;
		

	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		/**
		 * Router constructor.
		 *
		 * Sets up the Router with the values it needs determine or match routes
		 *
		 * Parameters are all from the Sketchpad config file
		 *
		 * @param   string[]    $paths      an array of paths
		 */
		public function __construct($paths)
		{
			$this->paths = (array) $paths;
		}


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS

		public function scan()
		{
			// reset
			$this->routes       = [];
			$this->controllers  = [];

			// scan
			foreach ($this->paths as $name => $path)
			{
				$root               = strpos($path, '/') === 0 ? $path : base_path($path);
				if (file_exists($root))
				{
					$scanner            = new Scanner($root, $name);
					$scanner->start();
					$this->routes       = array_merge($this->routes, $scanner->routes);
					$this->controllers  = array_merge($this->controllers, $scanner->controllers);
				}
			}

			// save routes
			Session::put('sketchpad.routes', $this->routes);

			// save method parameter types
			//ParamTypeManager::create()->saveAll($this->controllers);

			// return
			return $this;
		}

        /**
         * Reverse route lookup
         *
         * Compares a given route against routes determined when controllers were scanned
         *
         * Determines the controller, method and parameters to call if there is a match
         *
         * @param   string $route
         * @param $params
         * @return ControllerReference|FolderReference|null
         */
		public function getCall($route, array $params = [])
		{
			// variables
			$route      = AbstractScanner::folderize($route);
			$routes     = $this->getRoutes();

			// debug
			// pr($route, $routes);

			// first, attempt to find an exact match
			// an exact match will either be a controller or a folder
			if(isset($routes[$route]))
			{
				return $routes[$route];
			}

			// otherwise, the passed path will be at least a "controller/method" in which case,
            // we need to find the nearest partial match, then extract the component parts
			else
			{
				// variables
				/** @var CallReference $ref */
				$ref    = null;

				// loop over routes and grab matches
				// the last full match will be the one that we want
				foreach($routes as $key => $value)
				{
					//pr('KEY', $key, $value);
					if(strpos($route, $key) === 0)
					{
						$ref        = $value;
					}
				}

				// debug
				//pr('REF', $ref);

				// if we got a matching route, update the ref with method and params
				if($ref instanceof ControllerReference)
                {
                    return CallReference::fromControllerRef($ref)
	                    ->setMethod($route)
	                    ->setParams($params);
				}

				if($ref instanceof ControllerErrorReference)
                {
                    return CallReference::fromRef($ref)
	                    ->setMethod($route)
	                    ->setParams($params);
				}

			}

			// return
			return null;
		}

		public function getRoutes()
		{
			// existing routes
			if($this->routes)
			{
				return $this->routes;
			}

			// saved routes
			$routes = Session::get('sketchpad.routes');
			if($routes)
			{
				$this->routes = $routes;
				return $routes;
			}

			// scan routes
			$this->scan();
			return $this->routes;
		}

		public function getFolders()
		{
			return array_filter($this->routes, function($ref){ return $ref instanceof FolderReference; });
		}

		public function getControllers()
		{
			return $this->controllers;
		}

		public function getController($route)
		{
			// variables
			$route   = strtolower($route);
			$routes = $this->getRoutes();

			// filter
			foreach($routes as /** @var ControllerReference */$ref)
			{
				if(strtolower($ref->route) == $route)
				{
					// TODO not sure this is needed any more
					// check if the file still exists
					if(!$ref->exists())
					{
						return (object) ["error" => "The file '{$ref->path}' does not exist"];
					}

					// otherwise, get a reference
					$instance = Controller::fromPath(base_path($ref->path), $ref->route);

					// update session
					Session::put('sketchpad.routes.' . $ref->route, $instance->getReference());

					// return
					return $instance;
				}
			}

			return (object) ["error" => "Invalid controller route '$route'"];
		}
	
}