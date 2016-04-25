<?php namespace davestewart\doodle\services;

use App;
use davestewart\doodle\objects\DoodleConfig;
use davestewart\doodle\objects\file\File;
use davestewart\doodle\objects\file\Folder;
use davestewart\doodle\objects\reflection\Controller;
use davestewart\doodle\objects\route\ControllerReference;
use davestewart\doodle\objects\route\PathReference;
use Illuminate\Support\Facades\Input;
use ReflectionMethod;
use Route;


/**
 * Class DoodleService
 *
 * @package davestewart\doodle\services
 */
class DoodleService extends DoodleConfig
{

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * @var
		 */
		protected $config;

		/**
		 * The service that determines available routes, and matches routes to controllers when one is called
		 * 
		 * @var RouteService
		 */
		protected $routes;
	

	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION
	
		public function init()
		{
			// config
			$config         = (object) config('doodle');
			$this->path     = base_path($config->path);
			$this->route    = $config->route;
			$this->config   = $config;
			

			// routing
			$namespace      = 'davestewart\doodle\controllers';
			$parameters     =
			[
				'namespace'     => $namespace,
				'middleware'    => 'web',
			];
			
			// add main doodle routes
			Route::group($parameters, function ($router) use ($config)
			{
				Route::get  ($config->route, 'DoodleController@index');
				Route::post ($config->route, 'DoodleController@create');
				Route::match(['GET', 'POST'], $config->route . '{params?}', 'DoodleController@call')->where('params', '.*');
			});

			// determine remaining controller routes
			$this->routes = new RouteService($this->route, $this->path, $config->namespace);

			//dd($this);
		}


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS

		public function data($path)
		{
			$variables          = $this->variables();
			$variables['data']  = $this->getFolder($path);
			return $variables;
		}

		public function variables()
		{
			$config = (array) $this->config;
			return $config;
		}

		/**
		 * Gets the members for an object
		 *
		 * @param   string $path
		 * @return  array
		 */
		public function getFolder($path = '', $recursive = false)
		{
			if($path == '')
			{
				$path = $this->path;
			}
			return file_exists($path)
						? new Folder($path, $recursive)
						: null;
		}

		public function getController($path)
		{
			return file_exists($path)
				? new Controller($path)
				: null;
		}

		public function create($path, $members, $options)
		{

		}

		public function routeFromPath($path)
		{
			$path   = str_replace($this->path, '', $path);
			$path   = str_replace('Controller.php',  '', $path);
			$path   = strtolower($path) . '/';
			return $this->route . $path;
		}

		public function call($uri)
		{
			// get variables
			$json   = Input::get('json', 0);
			$base   = $this->route;
			$ref    = $this->routes->getRoute($base . $uri);

			// attempt to call controller
			if($ref instanceof ControllerReference)
			{
				// controller has method
				if($ref->method)
				{
					// test controller / method exists
					try
					{
						new ReflectionMethod($ref->class, $ref->method);
					}
					catch(\Exception $e)
					{
						if($e instanceof \ReflectionException)
						{
							$doodle = str_replace($base, '', $ref->route) . $ref->method . '/';
							$this->abort($doodle, 'method');
						}
					}
	
					// call and return the controller
					return $this->routes->call($ref->class, $ref->method, $ref->params);
				}
				
				// just controller				
				$controller = $this->getController($ref->path);
				return $json
					? $controller
					: view('doodle::nav.controller', compact('controller'));

			}

			// if folder, return the contents of that folder as json
			if($ref instanceof PathReference)
			{
				$data = $this->getFolder($ref->path);
				return $json
					? $data
					: view('doodle::nav.folder', compact('data'));
			}

			// otherwise, there's nothing to call, so 404
			$this->abort($uri, 'path');
		}

		protected function abort($uri, $type = '')
		{
			App::abort(404, "The requested Doodle $type '$uri' does not exist");
		}


}