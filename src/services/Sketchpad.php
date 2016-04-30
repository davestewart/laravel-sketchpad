<?php namespace davestewart\sketchpad\services;

use App;
use davestewart\sketchpad\objects\AbstractService;
use davestewart\sketchpad\objects\SketchpadConfig;
use davestewart\sketchpad\objects\file\Folder;
use davestewart\sketchpad\objects\reflection\Controller;
use davestewart\sketchpad\objects\route\ControllerReference;
use davestewart\sketchpad\objects\route\FolderReference;
use Illuminate\Support\Facades\Input;
use ReflectionMethod;
use Route;


/**
 * Class Sketchpad
 *
 * @package davestewart\sketchpad\services
 */
class Sketchpad extends AbstractService
{

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * @var SketchpadConfig
		 */
		protected $config;

		/**
		 * The service that determines available routes, and matches routes to controllers when one is called
		 * 
		 * @var Router
		 */
		protected $router;
	

	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION
	
		public function init()
		{
			// config
			$config         = new SketchpadConfig();
			$this->path     = base_path($config->path);
			$this->route    = $config->route;
			$this->config   = $config;

			// determine remaining controller routes
			$this->router   = new Router($this->route, $this->path, $config->namespace);

			// routing
			$parameters     =
			[
				'namespace'     => 'davestewart\sketchpad\controllers',
				'middleware'    => $config->middleware,
			];

			// add main sketchpad routes
			Route::group($parameters, function ($router) use ($config)
			{
				Route::post ($config->route . ':setup', 'SketchpadController@setup');
				Route::post ($config->route . ':create', 'SketchpadController@create');
				Route::get($config->route . ':{command}/{data?}', 'SketchpadController@command')->where('data', '.*');
				Route::match(['GET', 'POST'], $config->route . '{params?}', 'SketchpadController@call')->where('params', '.*');
			});

			// debug
			//dd($this->router->routes);
		}


	// -----------------------------------------------------------------------------------------------------------------
	// ACCESSORS

		public function getVariables()
		{
			$data['route']      = $this->route;
			$data['theme']      = $this->config->theme;
			$data['assets']     = $this->config->assets;
			$data['routes']     = $this->router->routes;
			return $data;
		}

		/**
		 * Gets the core data for the main sketchpad view
		 *
		 * @param      $path
		 * @param null $controller
		 * @return array
		 */
		public function getData($path, $controller = null)
		{
			$data = $this->getVariables();
			$data['folder']     = $this->getFolder($path);
			if($controller)
			{
				$data['controller'] = $controller;
			}
			return $data;
		}

		/**
		 * Gets folder data (subfolders and controllers) for an absolute file path
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

		/**
		 * Gets controller data for an absolute file path
		 *
		 * @param $path
		 * @return Controller|null
		 */
		public function getController($path)
		{
			return file_exists($path)
				? new Controller($path)
				: null;
		}

		/**
		 * Determines the route uri for an absolute file path
		 *
		 * @param $path
		 * @return string
		 */
		public function getRouteFromPath($path)
		{
			$path   = str_replace($this->path, '', $path);
			$path   = str_replace('Controller.php',  '', $path);
			$path   = $this->folderize(strtolower($path));
			return $this->route . $path;
		}


	// ------------------------------------------------------------------------------------------------
	// ROUTING METHODS

		public function call($uri = '')
		{
			// get variables
			$html   = Input::get('html', 0);
			$json   = Input::get('json', 0);
			$base   = $this->route;
			$ref    = $this->router->getRoute($base . $uri);

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
							$sketchpad = str_replace($base, '', $ref->route) . $ref->method . '/';
							$this->abort($sketchpad, 'method');
						}
					}

					// call and return the controller
					return $this->router->call($ref->class, $ref->method, $ref->params);
				}

				// just controller
				$controller = $this->getController($ref->path)->process();
				if($json)
				{
					return json_encode($controller);
				}
				if($html)
				{
					$data = ['controller' => $controller];
					return view('sketchpad::nav.methods', $data);
				}
				else
				{
					$data = $this->getData($ref->folder, $controller);
					$data['uri']        = $this->route . $uri . '/';
					return view('sketchpad::content.index', $data);
				}

			}

			// if folder, return the contents of that folder as json
			if($ref instanceof FolderReference)
			{
				if($html || $json)
				{
					$data = $this->getFolder($ref->path);
					return $json
						? $data
						: view('sketchpad::html.folder', compact('data'));
				}
				else
				{
					$data   = $this->getData($ref->path);
					$folder = $data['folder'];
					$folder->process();
					$data['controller'] = $folder->controllers[0];
					$data['uri']        = $this->route . $uri . '/';

					return view('sketchpad::content.index', $data);
				}
			}

			// otherwise, there's nothing to call, so 404
			$this->abort($uri, 'path');
		}


	// ------------------------------------------------------------------------------------------------
	// UTILITIES

		protected function abort($uri, $type = '')
		{
			App::abort(404, "The requested Sketchpad $type '$uri' does not exist");
		}


}