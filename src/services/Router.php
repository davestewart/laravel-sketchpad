<?php namespace davestewart\sketchpad\services;

use App;
use davestewart\sketchpad\objects\AbstractService;
use davestewart\sketchpad\objects\route\ControllerReference;
use davestewart\sketchpad\objects\route\FolderReference;
use davestewart\sketchpad\objects\scanners\Scanner;
use davestewart\sketchpad\traits\GetterTrait;
use Route;

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
class Router extends AbstractService
{
	
	use GetterTrait;

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * @var Scanner
		 */
		protected $scanner;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		/**
		 * Router constructor.
		 *
		 * Sets up the Router with the values it needs determine or match routes
		 *
		 * Parameters are all from the Sketchpad config file
		 *
		 * @param   string   $route         the base r
		 * @param   string   $path
		 */
		public function __construct($route, $path)
		{
			$this->scanner = new Scanner($path, $route);
		}


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS
	
		public function scan()
		{
			$this->scanner->start();
		}
	
		/**
		 * Reverse route lookup
		 *
		 * Compares a given route against routes determined when the controllers folder was processed
		 *
		 * Determines the controller, method and parameters to call if there is a match
		 *
		 * @param   string  $route
		 * @return  FolderReference|ControllerReference|null
		 */
		public function getRoute($route)
		{
			// variables
			$route      = $this->folderize($route);
			$routes     = $this->scanner->getRoutes();

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
					$ref->params    = $segments;

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

		public function getFolders()
		{
			return array_filter($this->scanner->routes, function($ref){ return $ref instanceof FolderReference; });
		}

		public function getRoutes()
		{
			return $this->scanner->getRoutes();
		}

		public function getControllers()
		{
			return $this->scanner->controllers;
		}


	// ------------------------------------------------------------------------------------------------
	// ROUTING

		/**
		 * Wildcard routing method
		 *
		 * @param   string          $route          Base route, i.e. 'test'
		 * @param   string          $controller     Path to controller, i.e. 'TestController'
		 * @param   string|array    $verbs          Optional Route verb(s), i.e. 'get'; defaults to ['get', 'post']

		 * @usage   wildcard('test', 'TestController');
		 * @usage   wildcard('test', 'TestController', ['get']);
		 * @usage   wildcard('test', 'Test\TestController');
		 *
		 * @example	http://localhost/test/hello/world
		 *
		 * @author	Dave Stewart | dave@davestewart.io
		 */
		protected function wildcard($route, $controller, $verbs = ['get', 'post'])
		{
			// variables
			$stack      = Route::getGroupStack();
			$namespace  = end($stack)['namespace'];
			$controller = $namespace . '\\' . $controller;

			// routing
			Route::match($verbs, rtrim($route, '/') . '/{method}/{params?}', function($method, $params = null) use ($controller)
			{
				Router::call($controller, $method, $params);

			})->where('params', '.*');
		}



}
