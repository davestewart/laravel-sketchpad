<?php namespace davestewart\sketchpad\services;
use davestewart\sketchpad\objects\scanners\Finder;
use davestewart\sketchpad\objects\SketchpadConfig;

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
			$this->configPath   = config_path('sketchpad.php');
		}


	// -----------------------------------------------------------------------------------------------------------------
	// public methods

		public function index()
		{
			return redirect('/sketchpad/:setup');
		}

		public function check()
		{
			// config
			$config = new SketchpadConfig();
			
			// config
			if( ! file_exists($this->configPath) )
			{
				return $this->fail('config-form');
			}
			
			// return
			return true;
		}

		public function view()
		{
			// config
			$config = new SketchpadConfig();

			// variables
			$app  = app();
			$data = app(Sketchpad::class)->getVariables();
			$vars =
			[
				'configPath'        => $this->configPath,
				'config'            => $config,
				'ns'                => method_exists($app, 'getNamespace') ? $app->getNamespace() : 'App\\',
			];

			// return view
			return view('sketchpad::setup.pages.' . $this->view, array_merge($data, $vars));
		}

		public function form()
		{
			// variables
			$app  = app();
			$data = app(Sketchpad::class)->getVariables();
			$vars =
			[
				'config'            => $this->getDefaultConfig(),
				'ns'                => method_exists($app, 'getNamespace') ? $app->getNamespace() : 'App\\',
			];

			// return view
			return view('sketchpad::setup.pages.config-path', array_merge($data, $vars));
		}

		protected function getDefaultConfig()
		{
			// find folders
			$finder = new Finder();
			$finder->start();

			// config
			$config             = (object) ((array) new SketchpadConfig());
			$config->route      = ltrim($config->route, '/');
			$config->path       = str_replace(base_path() . '/', '', $finder->path) . 'Sketchpad/';
			$config->namespace  = $finder->namespace . '\\Sketchpad';

			// return
			return $config;
		}


		public function getView()
		{
			return $this->view;
		}

		public function makeConfig($input)
		{

			// config
			$path           = $this->configPath;
			$contents       = file_exists($path)
								? file_get_contents($path)
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
			file_put_contents($path, $contents);

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
