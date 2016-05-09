<?php namespace davestewart\sketchpad\services;

use App;
use davestewart\sketchpad\objects\AbstractService;
use davestewart\sketchpad\objects\SketchpadConfig;
use davestewart\sketchpad\objects\route\ControllerReference;
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
			$this->config   = $config;
			$this->route    = '/' . trim($config->route, '/') . '/';
			$this->path     = base_path($config->path);

			// routing
			$parameters     =
			[
				'namespace'     => 'davestewart\sketchpad\controllers',
				'middleware'    => $config->middleware,
			];

			// pushState main sketchpad routes
			Route::group($parameters, function ($router) use ($config)
			{
				//Route::get($config->route, 'SketchpadController@index');
				Route::match(['GET', 'POST'], $config->route . '{params?}', 'SketchpadController@call')->where('params', '.*');
				Route::post ($config->route . ':setup', 'SketchpadController@setup');
				Route::post ($config->route . ':create', 'SketchpadController@create');
				Route::get($config->route . ':{command}/{data?}', 'SketchpadController@command')->where('data', '.*');
			});

			// debug
			/*
			$finder     = new Finder();
			$finder->start();

			$scanner = new Scanner($finder->path . 'Sketchpad/');
			$scanner
				->start()
				->save()
				->load();
			dd(\Session::get('sketchpad'));
			*/
		}


	// -----------------------------------------------------------------------------------------------------------------
	// ACCESSORS

		public function getVariables()
		{
			$data =
			[
				'route'     => $this->route,
				'theme'     => $this->config->theme,
				'assets'    => $this->config->assets,
			];
			return $data;
		}


	// ------------------------------------------------------------------------------------------------
	// ROUTING METHODS

		public function call($uri = '')
		{

			// ------------------------------------------------------------------------------------------------
			// variables

				$call       = Input::get('call', 0);
				$json       = Input::get('json', 0);
				$base       = $this->route;


			// ------------------------------------------------------------------------------------------------
			// router

				/** @var Router $router */
				$router     = new Router($this->route, $this->path);

				// if we're not calling, consider this a new page load, and re-scan
				if( ! $call )
				{
					$router->scan();
				}

				/** @var ControllerReference $ref */
				$ref        = $router->getRoute($base . $uri);


			// ------------------------------------------------------------------------------------------------
			// debug

				$debug =
				[
					'call' => $call,
					'json' => $json,
					'base' => $base,
					'ref'  => $ref,
				];
				//pr($debug);


			// ------------------------------------------------------------------------------------------------
			// process route

				// attempt to call controller
				if($call)
				{
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
								$sketchpad = str_replace($base, '', $ref->route) . $ref->method . '/';
								$this->abort($sketchpad, 'method');
							}
						}

						// call and return the controller
						return static::exec($ref->class, $ref->method, $ref->params);
					}

					// if there's not a valid controller or method, it's a 404
					$this->abort($uri, 'path');
				}

				// build the index page
				$data           = $this->getVariables();
				$data['app']    = file_get_contents(base_path('vendor/davestewart/sketchpad/resources/views/vue/app.vue'));
				$data['data']   =
				[
					//'route'         => $this->route . $uri . '/',
					'controllers'   => $router->getControllers(),
					//'controller'    => (object) [],
					//'method'        => (object) [],
				    'ref'           => $ref,
			    ];
				return view('sketchpad::index', $data);
		}


		/**
		 * Calls a controller and methods, resolving any dependency injection
		 *
		 * @param   string      $controller     The FQ name of the controller
		 * @param   string      $method         The name of the method
		 * @param   array|null  $params         An optional arr ay of parameters
		 * @return  mixed                       The result of the call
		 */
		public static function exec($controller, $method, $params = null)
		{
			// method
			$callable = $controller . '@' . $method;
	
			// call
			if($params)
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
					preg_match('/<(required|optional)> (?:([\\\\a-z\d_]+) )?(?:\\$(\w+))(?: = (\S+))?/i', (string) $param, $matches);
	
					// assign untyped segments
					if($matches[2] == null)
					{
						$args[$matches[3]] = array_shift($values);
					}
				}
	
				// append any remaining values
				$values = array_merge($args, $values);
	
				// call
				return App::call($callable, $values);
			}
			else
			{
				return App::call($callable);
			}
		}

	// ------------------------------------------------------------------------------------------------
	// UTILITIES

		protected function abort($uri, $type = '')
		{
			App::abort(404, "The requested Sketchpad $type '$uri' does not exist");
		}


}