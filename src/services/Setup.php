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


	// ------------------------------------------------------------------------------------------------
	// utility methods

		protected function fail($view)
		{
			$this->view = $view;
			return false;
		}


}
