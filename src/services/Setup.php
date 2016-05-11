<?php namespace davestewart\sketchpad\services;

/**
 * Checks setup is OK and advises what to do if not
 *
 * @package davestewart\sketchpad\services
 */
class Setup
{

	// -----------------------------------------------------------------------------------------------------------------
	// properties

		protected $configPath;
		protected $config;
		protected $view;


	// ------------------------------------------------------------------------------------------------
	// instantiation

		public function __construct()
		{
			$this->configPath = config_path('sketchpad.php');
			$this->config     = (object) config('sketchpad');
		}


	// -----------------------------------------------------------------------------------------------------------------
	// public methods

		public function check()
		{
			// config
			if( ! file_exists($this->configPath) )
			{
				return is_writable(config_path())
					? $this->fail('config-form')
					: $this->fail('config-path');
			}

			// check controllers folder exists
			$controllerPath = rtrim(base_path( $this->config->path ), '/') . '/';
			if( ! file_exists($controllerPath) )
			{
				return $this->fail('controller-path');
			}

			// controllers
			$files = glob($controllerPath . '*Controller.php');
			if( count($files) === 0 )
			{
				return $this->fail('controller-count');
			}

			// return
			return true;
		}

		public function view()
		{
			// variables
			$app  = app();
			$data = app(Sketchpad::class)->getVariables();
			$vars =
			[
				'configPath'        => $this->configPath,
				'config'            => $this->config,
				'ns'                => method_exists($app, 'getNamespace') ? $app->getNamespace() : 'App\\',
			];

			// return view
			return view('sketchpad::setup.pages.' . $this->view, array_merge($data, $vars));
		}

		public function makeConfig($input)
		{

			// config
			$config         = config_path('sketchpad.php');
			$contents       = file_exists($config)
								? file_get_contents($config)
								: file_get_contents(base_path('vendor/davestewart/sketchpad/publish/config/config.php'));

			// helper function
			$update = function ($name, $trim) use($input, & $contents)
			{
				$value      = $input[$name];
				$value      = trim($value, '\\/');
				$value      = trim($value, $trim) . $trim;
				$contents   = preg_replace("/('$name'[^']+?)'([^']+?)'/", "$1'$value'", $contents);
			};

			// massage input
			$update('route', '/');
			$update('path', '/');
			$update('namespace', '\\');
			$update('assets', '/');

			// update double-slashes
			$contents       = str_replace('\\', '\\\\', $contents);

			// write the file
			file_put_contents($config, $contents);

			// run the next stage of setup
			return true;
		}


	// ------------------------------------------------------------------------------------------------
	// utility methods

		protected function fail($view)
		{
			$this->view = $view;
			return false;
		}


}
