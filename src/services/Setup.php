<?php namespace davestewart\sketchpad\services;

use Config;
use davestewart\sketchpad\config\SketchpadConfig;
use Illuminate\Http\Request;
use davestewart\sketchpad\objects\install\JSON;
use davestewart\sketchpad\config\Paths;
use davestewart\sketchpad\config\InstallerSettings;
use davestewart\sketchpad\objects\scanners\Finder;


/**
 * Checks setup is OK and advises what to do if not
 *
 * @package davestewart\sketchpad\services
 */
class Setup
{

	// -----------------------------------------------------------------------------------------------------------------
	// public methods

		public function index()
		{
		    // variables
            $request = app(Request::class);
		    $path = 'sketchpad/setup';

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
            $finder         = new Finder();
            $finder->start();

            // config
            $config         = app(SketchpadConfig::class);

            // base name
            $basePath       = Paths::fix(base_path('/'));
            $baseSegments   = explode('/', rtrim($basePath, '/'));
            $baseName       = array_pop($baseSegments) . '/';

            // view path
            $viewPaths      = Config::get('view.paths');
            $viewPath       = substr(Paths::fix($viewPaths[0]), strlen($basePath));

			// variables
			$app            = app();
			$data =
			[
				'route'     => $config->route,
				'assets'    => $config->route . 'assets/',
				'settings' =>
				[
					'route'             => $config->route,
					'basepath'          => $basePath,
					'basename'          => $baseName,
                    'viewpath'          => $viewPath,
                    'storagepath'       => Paths::relative($config->settings->src),
                    'controllerpath'    => trim(Paths::relative($finder->path), '/'),
					'namespace'         => method_exists($app, 'getNamespace')
                                            ? trim($app->getNamespace(), '\\')
                                            : 'App\\',
                    'namespaces'        => (new JSON('composer.json'))->get('autoload.psr-4')
				]
			];

			// return view
			return view('sketchpad::setup', $data);
		}

		public function disabled()
		{
			$config = app(SketchpadConfig::class);
			$data =
			[
				'route' => $config->route,
				'assets' => '/' . $config->assets,
				'path' => substr(storage_path('sketchpad/admin.json'), strlen(base_path()) + 1)
			];
			die(view('sketchpad::no-setup', $data));
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
