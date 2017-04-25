<?php namespace davestewart\sketchpad\services;

use App;
use davestewart\sketchpad\config\SketchpadSettings;
use davestewart\sketchpad\objects\reflection\Controller;
use davestewart\sketchpad\objects\route\CallReference;
use davestewart\sketchpad\objects\route\ControllerReference;
use davestewart\sketchpad\config\SketchpadConfig;
use davestewart\sketchpad\traits\GetterTrait;
use davestewart\sketchpad\utils\Html;
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
		public static $route;

		/**
		 * The currently-called params as key => value pairs
		 *
		 * @var mixed[]
		 */
		public static $params;

		/**
		 * Any submitted form data
		 *
		 * @var mixed[]
		 */
		public static $form;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION
	
		public function __construct()
		{
			$this->config = app(SketchpadConfig::class);
		}


	// -----------------------------------------------------------------------------------------------------------------
	// SETUP

		public function init($scan = false)
		{
			$this->router = new Router($this->config->controllers);
			if($scan)
			{
				//pr($this->router);
				$this->router->scan();
				//pd($this->router);
			}
			return $this;
		}

		public function isInstalled ()
        {
            return $this->config->settings->exists();
        }


	// ------------------------------------------------------------------------------------------------
	// GETTERS

		/**
		 * Returns a sketchpad\objects\reflection\Controller that can be converted to JSON
		 *
		 * @param   string      $route  The relative route to the controller
		 * @return  Controller          The Controller
		 */
		public function getController($route = null)
		{
		    $router = $this->init($route == null)->router;
			return $route
                ? $router->getController($route)
                : $router->getControllers();
		}


	// ------------------------------------------------------------------------------------------------
	// ROUTING METHODS

        /**
         * Initial function that works out the controller, method and parameters to call from the URI string
         *
         * @param string $route
         * @param array $params
         * @param array $form
         * @return mixed|string
         */
		public function run($route = '', array $params, array $form = null)
		{
			// set up the router, but don't scan
			$this->init();

			/** @var CallReference $ref */
			$ref = $this->router->getCall($route, $params);

			//vd([$ref, $route, $params]);
            //exit;

			// controller has method
			if($ref instanceof CallReference)
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
						//$sketchpad = str_replace($this->config->route, '', $ref->route) . $ref->method . '/';
						$this->abort($ref->route . '::' . $ref->method . '()', 'method');
					}
				}

				// assign static properties
				Sketchpad::$route = $ref->route . $ref->method . '/';
				Sketchpad::$params = $ref->params;
				Sketchpad::$form = empty($form) ? null : $form;

				// get controller response or content
				ob_start();
				$response   = $this->exec($ref->class, $ref->method, $ref->params);
				$content    = ob_get_contents();
				ob_end_clean();

				// handle echoed output
				if ($content)
				{
					return $content;
				}

				// handle laravel view responses
				if ($response instanceof \Illuminate\Contracts\View\View)
				{
					return $response;
				}

				// handle laravel json responses
				if ($response instanceof \Illuminate\Http\JsonResponse)
				{
					return json($response->getData());
				}

				// handle objects
				if ($response instanceof Illuminate\Contracts\Support\Jsonable || !is_scalar($response))
				{
					return json($response);
				}

				// anything else
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