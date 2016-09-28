<?php namespace davestewart\sketchpad\services;

use Config;
use Illuminate\Http\Request;
use davestewart\sketchpad\objects\install\JSON;
use davestewart\sketchpad\objects\settings\Paths;
use davestewart\sketchpad\objects\settings\InstallerSettings;
use davestewart\sketchpad\objects\scanners\Finder;


/**
 * Checks setup is OK and advises what to do if not
 *
 * @package davestewart\sketchpad\services
 */
class Setup
{

	// -----------------------------------------------------------------------------------------------------------------
	// properties




    // ------------------------------------------------------------------------------------------------
	// instantiation

        public function __construct()
		{

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

            // paths
            $paths  = new Paths();

            // functions
            function path($path)
            {
                return trim(str_replace(base_path(), '', $path), '/');
            }

            // base name
            $basePath   = base_path() . '/';
            $temp       = explode('/', base_path());
            $baseName   = array_pop($temp) . '/';

            // view path
            $temp       = Config::get('view.paths');
            $viewPath   = substr($temp[0], strlen(base_path() . '/'));

			// variables
            $assets = $paths->publish('assets/');
			$app    = app();
			$data   = app(Sketchpad::class)->getVariables();
			$vars   =
			[
				'settings' =>
				[
					'basepath'          => $basePath,
					'basename'          => $baseName,
                    'viewpath'          => $viewPath,
					'configpath'        => $paths->relative(config_path('sketchpad.php')),
                    'controllerpath'    => trim($paths->relative($finder->path), '/'),
					'namespace'         => method_exists($app, 'getNamespace')
                                            ? trim($app->getNamespace(), '\\')
                                            : 'App\\',
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
            $settings = new InstallerSettings();
            return $settings->save($input);
		}

        public function loadData()
        {
            $settings = new InstallerSettings();
            return $settings;
		}


}
