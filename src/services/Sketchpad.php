<?php namespace davestewart\sketchpad\services;

use App;
use davestewart\sketchpad\objects\reflection\Controller;
use davestewart\sketchpad\objects\route\ControllerReference;
use davestewart\sketchpad\objects\scanners\Finder;
use davestewart\sketchpad\objects\scanners\Scanner;
use davestewart\sketchpad\objects\SketchpadConfig;
use davestewart\sketchpad\traits\GetterTrait;
use Illuminate\Support\Facades\Input;
use ReflectionMethod;


/**
 * Class Sketchpad
 *
 * @property Router $router
 * @property SketchpadConfig $config
 * @property ControllerReference $route
 */
class Sketchpad
{

	use GetterTrait;

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
	
		/**
		 * Any currently-called route, up to the method, but not including the params
		 *
		 * @var string
		 */
		protected $route;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION
	
		public function __construct()
		{
			$this->config = new SketchpadConfig();
		}


	// -----------------------------------------------------------------------------------------------------------------
	// SETUP

		public function init($scan = false)
		{
			$this->router = new Router($this->config->route, $this->config->paths);
			if($scan)
			{
				//pr($this->router);
				$this->router->scan();
				//pd($this->router);
			}
			return $this;
		}

		public function getVariables()
		{
			$data =
			[
				'route'     => $this->config->route,
				'assets'    => $this->config->assets,
			];
			return $data;
		}
	

	// ------------------------------------------------------------------------------------------------
	// GETTERS

		/**
		 * Returns a view for a single "page" type
		 *
		 * @param $page
		 * @return \Illuminate\View\View
		 */
		public function getPage($page)
		{
			$data               = $this->getVariables();
			$data['folders']    = $this->init(true)->router->getFolders();
			return view('sketchpad::pages.' . $page, $data);
		}

		/**
		 * Returns a sketchpad\objects\reflection\Controller that can be converted to JSON
		 *
		 * @param   string      $path   The absolute file path to the controller
		 * @return  Controller          The Controller
		 */
		public function getController($path)
		{
			return $this->init()->router->getController($path);
		}


	// ------------------------------------------------------------------------------------------------
	// ROUTING METHODS

		/**
		 * Index route, shows the main Sketchpad UI
		 *
		 * @return \Illuminate\View\View
		 */
		public function index()
		{
			// set up the router and rescan to get all data
			$this->init(true);

			// build the index page
			$data           = $this->getVariables();
			$data['data']   =
			[
				'controllers'   => $this->router->getControllers(),
			];

			// return
			return view('sketchpad::index', $data);
		}

		/**
		 * Initial function that works out the controller, method and parameters to call from the URI string
		 *
		 * @param string $route
		 * @return mixed|string
		 */
		public function call($route = '')
		{
			// set up the router, but don't scan
			$this->init();

			//pd($this->router);

			/** @var ControllerReference $ref */
			$ref = $this->router->getRoute($this->config->route . $route);

			// controller has method
			if($ref instanceof ControllerReference && $ref->method)
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
						$sketchpad = str_replace($this->config->route, '', $ref->route) . $ref->method . '/';
						$this->abort($sketchpad, 'method');
					}
				}

				// assign controller property
				$this->route = $ref->route . $ref->method . '/';

				// setup headers for AJAX call matching
				header('X-Request-ID:' . Input::get('requestId', ''));

				// get controller response
				ob_start();
				$response   = $this->exec($ref->class, $ref->method, $ref->params);
				$content    = ob_get_contents();
				ob_end_clean();

				// determine response or any echoed content
				$response = $response
					? $response
					: $content;

				// return markdown immediately, as some versions of laravel may be replacing headers
				$headers = implode(' ', headers_list());
				if(strstr($headers, 'Content-type: text/markdown') !== false)
				{
					die($content);
				}

				// otherwise, return response
				return $response;

			}

			// if there's not a valid controller or method, it's a 404
			$this->abort($route, 'path');
			return false;
		}


	// ------------------------------------------------------------------------------------------------
	// UTILITIES

		/**
		 * Calls a controller and methods, resolving any dependency injection
		 *
		 * @param   string      $controller     The FQ name of the controller
		 * @param   string      $method         The name of the method
		 * @param   array|null  $params         An optional array of parameters
		 * @return  mixed                       The result of the call
		 */
		public function exec($controller, $method, $params = null)
		{
			$callable = "$controller@$method";
			return $params
				? App::call($callable, $this->getCallParams($controller, $method, $params))
				: App::call($callable);
		}

		/**
		 * Gets method parameters in the correct format to do an App:call() with dependency injection
		 *
		 * @param   string          $controller
		 * @param   string          $method
		 * @param   string|array    $params
		 * @return  array
		 */
		protected function getCallParams($controller, $method, $params)
		{
			// variables
			$values     = is_array($params) ? $params : explode('/', $params);
			$ref        = new ReflectionMethod($controller, $method);
			$params     = $ref->getParameters();
			$args       = [];
	
			// map route segments to the method's parameters
			foreach ($params as /** @var \ReflectionParameter */ $param)
			{
				// parse signature [match, optional, type, name, default]
				preg_match('/<(required|optional)> (?:([\\\\a-z\d_]+) )?(?:\\$(\w+))(?: = (\S+))?/i', (string)$param, $matches);
	
				// assign untyped segments
				if ($matches[2] == null)
				{
					$args[$matches[3]] = array_shift($values);
				}
			}
	
			// append any remaining values
			return array_merge($args, $values);
		}

		/**
		 * Aborts with a message
		 *
		 * @param   string  $uri    The route that was called by the front end
		 * @param   string  $type   An object type, such as a controller, method, folder
		 */
		protected function abort($uri, $type = '')
		{
			App::abort(404, "The requested Sketchpad $type '$uri' does not exist");
		}


}