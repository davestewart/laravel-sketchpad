<?php namespace davestewart\sketchpad\services;

use Config;
use davestewart\sketchpad\install\objects\JSON;
use davestewart\sketchpad\install\Paths;
use davestewart\sketchpad\install\Settings;
use davestewart\sketchpad\objects\scanners\Finder;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\Request;


/**
 * Checks setup is OK and advises what to do if not
 *
 * @package davestewart\sketchpad\services
 */
class Setup
{

	// -----------------------------------------------------------------------------------------------------------------
	// properties

        protected $paths;


    // ------------------------------------------------------------------------------------------------
	// instantiation

        public function __construct()
		{
		    $this->paths = new Paths();
		}


	// -----------------------------------------------------------------------------------------------------------------
	// public methods

		public function index()
		{
		    // variables
            $request = app(Request::class);
		    $path = 'sketchpad/:setup';

            // redirect
			return $request->path() !== $path
                ? redirect($path)
                : $this->view();
		}


    // ------------------------------------------------------------------------------------------------
    // setup

        /**
         * Shows the setup form view
         *
         * @return mixed
         */
		public function view()
		{
		    // default variables
            $finder = new Finder();
            $finder->start();

            // functions
            function path($path)
            {
                return trim(str_replace(base_path(), '', $path), '/');
            }

            // view path
            $temp       = Config::get('view.paths');
            $viewPath   = substr($temp[0], strlen(base_path() . '/'));

            // base name
            $temp       = explode('/', base_path());
            $baseName   = array_pop($temp) . '/';

			// variables
            $assets = base_path('vendor/davestewart/sketchpad/publish/assets/');
			$app    = app();
			$data   = app(Sketchpad::class)->getVariables();
			$vars   =
			[
			    'script' => file_get_contents($assets . 'app.js'),
			    'styles' => file_get_contents($assets . 'app.css'),
				'settings' =>
				[
					'basepath'          => base_path() . '/',
					'basename'          => $baseName,
                    'viewpath'          => $viewPath,
					'configpath'        => path($this->paths->config),
                    'controllerpath'    => path($finder->path),
					'namespace'         => method_exists($app, 'getNamespace') ? trim($app->getNamespace(), '\\') : 'App\\',
                    'namespaces'        => (new JSON('composer.json'))->get('autoload.psr-4')
				]
			];

			// return view
			return view('sketchpad::setup', array_merge($data, $vars));
		}


    // ------------------------------------------------------------------------------------------------
    // form

        public function saveData($input)
        {
            $settings = new Settings();
            return $settings->save($input);
		}

        public function loadData()
        {
            $settings = new Settings();
            return $settings;
		}


}
