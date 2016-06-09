<?php namespace davestewart\sketchpad\services;

use davestewart\sketchpad\objects\reflection\Controller;
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
		 * @var string
		 */
		protected $route;
	
		/**
		 * @var string[]
		 */
		protected $paths;
	
		/**
		 * @var RouteReference[]
		 */
		protected $routes;
	
		/**
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
		 * @param   string      $route      the base route for all controllers
		 * @param   string[]    $paths      an array of paths
		 */
		public function __construct($route, $paths)
		{
			$this->route = $route;
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
				$scanner            = new Scanner($root, $this->route . $name . '/');
				$scanner->start();
				$this->routes       = array_merge($this->routes, $scanner->routes);
				$this->controllers  = array_merge($this->controllers, $scanner->controllers);
			}

			// save routes
			Session::put('sketchpad.routes', $this->routes);

			// save controller types
			ParamTypeManager::create()->saveAll($this->controllers);

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
		 * @param   string  $route
		 * @return  FolderReference|ControllerReference|null
		 */
		public function getRoute($route)
		{
			// variables
			$route      = AbstractScanner::folderize($route);
			$routes     = $this->getRoutes();

			// debug
			//pr($route, $routes);

			// first, attempt to find an exact match
			// an exact match will either be a controller or a folder
			if(isset($routes[$route]))
			{
				return $routes[$route];
			}

			// otherwise, the passed path will be at least a "controller/method" and possibly
			// a "controller/method/with/some/parameters" in which case, we need to find
			// the nearest partial match, then extract the component parts
			else
			{
				// variables
				/** @var ControllerReference $ref */
				$ref    = null;
				$match  = '';

				// loop over routes and grab matches
				// the last full match will be the one that we want
				foreach($routes as $key => $value)
				{
					//pr('KEY', $key, $value);
					if(strpos($route, $key) === 0)
					{
						$match      = $key;
						$ref        = $value;
					}
				}

				// debug
				//pr('REF', $ref);

				// if we got a matching route, update the ref with method and params
				if($ref)
				{
					// variables
					$methodUri      = trim(substr($route, strlen($match)), '/');
					$segments       = explode('/', $methodUri);

					// properties
					$ref->method    = array_shift($segments);
					$ref->params    = ParamTypeManager::create()->convert($segments, $ref->route . $ref->method . '/');

					// finally check if we have a folder with methods; this indicates a 404
					if($ref instanceof FolderReference && $ref->method)
					{
						return null;
					}

					// otherwise, return (controller) reference
					return $ref;
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

		public function getController($path)
		{
			if(file_exists($path))
			{
				// variables
				$path   = strtolower($path);
				$routes = $this->getRoutes();

				// filter
				foreach($routes as /** @var ControllerReference */$ref)
				{
					if(strtolower($ref->path) == $path)
					{

						$controller = new Controller($ref->path, $ref->route);
						ParamTypeManager::create()->saveOne($controller);
						return $controller;
					}
				}
			}
			return null;
		}
	
}


/**
 * Utility class to save, load and parse controller and method parameter types
 */
class ParamTypeManager
{

	// ------------------------------------------------------------------------------------------------
	// instantiation

		public static function create()
		{
			return new self;
		}


	// ------------------------------------------------------------------------------------------------
	// session

		/**
		 * Saves an array of controllers' parameter types to the session
		 *
		 * @param   Controller[]  $controllers
		 */
		public function saveAll($controllers)
		{
			$types = [];
			foreach($controllers as $controller)
			{
				$types = array_merge($types, $this->get($controller));
			}
			Session::forget('sketchpad.types');
			Session::put('sketchpad.types', $types);
		}

		/**
		 * Saves a single controller's parameter types to the session
		 *
		 * @param   Controller  $controller
		 */
		public function saveOne($controller)
		{
			$types = Session::get('sketchpad.types');
			$types = array_merge($types, $this->get($controller));
			Session::put('sketchpad.types', $types);
		}

		/**
		 * Loads (if teh route exists) a parameter array if saved in the session
		 *
		 * @param $route
		 * @return array
		 */
		public function loadOne($route)
		{
			return Session::get('sketchpad.types.' . $route);
		}


	// ------------------------------------------------------------------------------------------------
	// objects

		/**
		 * Gets a single controller's parameter types as a routes => types array
		 *
		 * @param   Controller  $controller
		 * @return  array
		 */
		public function get($controller)
		{
			$types = [];
			foreach($controller->methods as $method)
			{
				$params = array_map(function($param){ return $param->type; }, $method->params);
				if($params)
				{
					$types[$method->route] = $params;
				}
			}
			return $types;
		}

		/**
		 * Converts an array of parameters to the correct type
		 *
		 * Works by loading any saved array from the session, and converting
		 *
		 * @param   array   $params
		 * @param   string  $route
		 * @return  array
		 */
		public function convert($params, $route)
		{
			$types = $this->loadOne($route);
			if($types)
			{
				foreach ($params as $index => $value)
				{
					$type = $types[$index];
					if ($type == 'number')
					{
						$params[$index] = (float) $value;
					}
					else if ($type == 'boolean')
					{
						$params[$index] = $value === 'true';
					}
					else if($type == 'mixed')
					{
						$params[$index] = $this->cast($value);
					}
					else if($type == 'null' && $value == 'null')
					{
						$params[$index] = null;
					}
				}
			}

			return $params;
		}

		/**
		 * Parses an array of parameters to the correct type
		 *
		 * Works by guessing the types from the values
		 *
		 * @param   mixed[]     $params
		 * @return  mixed[]
		 */
		protected function parse($params)
		{
			foreach($params as $name => $value)
			{
				$params[$name] = $this->cast($value);
			}
			return $params;
		}

		/**
		 * Casts a string value to its appropriate type
		 *
		 * Works by guessing the types from the values
		 *
		 * @param   string      $value
		 * @return  mixed
		 */
		protected function cast($value)
		{
			if(is_numeric($value))
			{
				return (float) $value;
			}
			else if($value === 'true' || $value === 'false')
			{
				return $value === 'true';
			}
			return $value;
		}

}