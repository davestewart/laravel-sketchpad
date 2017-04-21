<?php namespace davestewart\sketchpad\services;

use davestewart\sketchpad\objects\install\ClassTemplate;
use davestewart\sketchpad\objects\install\Copier;
use davestewart\sketchpad\objects\install\Folder;
use davestewart\sketchpad\objects\install\JSON;
use davestewart\sketchpad\objects\install\Composer;
use davestewart\sketchpad\config\Paths;
use davestewart\sketchpad\config\InstallerSettings;
use Illuminate\Console\Command;

/**
 * Installs Sketchpad according to the settings saved by the GUI form
 */
class Installer
{

    // -----------------------------------------------------------------------------------------------------------------
    // PROPERTIES

    // variables

		/** @var JSON */
        protected $prefs;

        /** @var Paths */
        protected $paths;

    // state

        /** @var Log[] An array of installation step results */
        public $logs;

        /** @var bool Flag to indicate installation was a success or failure */
        public $state;

    // installers

        /** @var Composer */
        public $composer;

        /** @var JSON */
        public $admin;

        /** @var JSON */
        public $settings;

        /** @var Folder */
        public $controllers;

        /** @var ClassTemplate */
        public $controller;

        /** @var Folder */
        public $views;

        /** @var Copier */
        public $view;

        /** @var Copier */
		public $assets;

	// command

		/** @var Command */
		protected $command;



	// -----------------------------------------------------------------------------------------------------------------
    // INSTANTIATION

        public function __construct($command = null)
        {
            $this->state        = true;
            $this->prefs        = new InstallerSettings();
            $this->paths        = new Paths();
            $this->command      = $command;
            $this->initialize();
        }

        protected function initialize()
        {
            // variables
            $settings           = $this->prefs;
            $package            = $this->paths->package();

            // update compose
            if($this->prefs->autoloader)
            {
                $this->composer = new Composer();
            }

            // objects
            $this->controllers  = new Folder(   $settings->controllers);
            $this->controller   = new ClassTemplate( $package . 'setup/controllers/ExampleController.txt', $settings->controllers . '{filename}.php');
            $this->views        = new Folder(   $settings->views);
            $this->view         = new Copier(   $package . 'setup/views/example.blade.php', $settings->views);
	        $this->assets       = new Copier(   $package . 'setup/assets', base_path($settings->assets));
            $this->admin        = new JSON(     $package . 'config/admin.json', $this->paths->storage());
            $this->settings     = new JSON(     $package . 'config/settings.json', $this->paths->storage());
        }


    // ------------------------------------------------------------------------------------------------
    // install

        public function install()
        {
            if($this->composer)
            {
            	// update composer
        	    $this->info(' > Updating composer.json');
                $this->composer
                    ->set("autoload.psr-4.{$this->prefs->namespace}", $this->prefs->basedir)
                    ->create();

	            // generate autoload files
        	    $this->info(' > Running composer --dumpautoload');
	            $output = $this->composer->generateAutoload();
	            if ($this->command)
	            {
	            	$this->command->info($output);
	            }
            }

            $this->info(' > Creating controllers folder');
            $this->controllers
                ->create();

	        $this->info(' > Creating example controller');
            $this->controller
                ->setNamespace()
                ->create();

            $this->info(' > Creating views folder');
            $this->views
                ->create();

            $this->info(' > Creating example view');
            $this->view
                ->create();

            $this->info(' > Copying user assets');
            $this->assets
                ->create();

            $this->info(' > Copying admin settings');
            $this->admin
                ->create();
        }

        /**
         * Checks that the installer copied the correct files and made all the right updates
         */
		public function test()
		{
		    function writable ($folder)
		    {
			    return "Ensure <code>$folder</code> exists and is writable";
		    }

		    function copy ($src, $trg)
		    {
		    	$src = rel($src);
		    	$trg = rel($trg);
			    return "Copy <code>{$src}</code> to <code>{$trg}</code>";
		    }

		    function rel ($path)
		    {
		    	$base = base_path() . '/';
		    	return str_replace($base, '', $path);

		    }

		    $this->logs = [];

            if($this->composer)
            {
            	// update composer
                $key    = $this->prefs->namespace;
                $value  = $this->prefs->basedir;
                $this->composer->has('autoload.psr-4.' . $key)
                    ? $this->pass('Composer autoload config updated')
                    : $this->fail('The PSR-4 autoload entry was not added to your composer.json', "Make <code>composer.json</code> writeable, or manually add a new entry in <code>autoload.psr-4</code>: <code>\"$key\" : \"$value\"</code>");

	            $this->composer->hasPSR4Class($key)
		            ? $this->pass('Composer autoload updated')
		            : $this->fail('Composer autoload was not updated', "Try running the command <code>composer dumpautoload</code> from the project root");
            }

            $this->controllers->exists()
                ? $this->pass('Controller folder created')
                : $this->fail('The sketchpad controllers folder was not found', "Create the folder <code>" .rel($this->controllers->src). "</code>");

            $this->controller->exists()
                ? $this->pass('Example controller created')
                : $this->fail('The sketchpad example controller could not be found', "Create the controller <code>" .rel($this->controller->trg)."</code>");

            $this->controller->loads()
                ? $this->pass('Example controller loaded')
                : $this->fail('Unable to load example controller', "Check the namespace declaration in <code>" .rel($this->controller->trg). "</code>");

            $this->views->exists()
                ? $this->pass('Views folder created')
                : $this->fail('The sketchpad views folder was not found', writable(dirname($this->views->src)));

            $this->view->exists()
                ? $this->pass('Example view copied')
                : $this->fail('The sketchpad example view was not found', copy($this->view->src, $this->view->trg));

            $this->assets->exists()
                ? $this->pass('User assets copied')
                : $this->fail('The sketchpad assets folder was not copied', copy($this->assets->src, $this->assets->trg));

            $this->admin->exists()
                ? $this->pass('Admin settings copied')
                : $this->fail('The admin settings file was not copied', copy($this->admin->src, $this->admin->trg));

            // only save settings if everything went ok
            if ($this->state)
            {
	            $this->info(' > Saving settings.json');
	            $this->settings
		            ->set('route', $this->prefs->route)
		            ->set('paths.controllers.0.path', $this->prefs->controllers)
		            ->set('paths.views', $this->prefs->views)
		            ->set('paths.assets', $this->prefs->assets)
		            ->create();

	            $this->settings->exists()
		            ? $this->pass('Settings created')
		            : $this->fail('The sketchpad settings file could not be found', writable(rel(storage_path('sketchpad/'))));
            }

            $this->saveLogs();

            return $this->state;
		}

		protected function pass($title)
        {
            $this->log(true, $title);
        }

		protected function fail($title, $text)
        {
            $this->state = false;
            $this->log(false, $title, $text);
        }

		protected function log($state, $title, $text = '')
        {
            $this->logs[] = new Log($state, $title, $text);
            if ($text)
            {
                $text .= "\n";
            }
            $this->info("$title\n$text");
        }

        protected function info($text)
        {
            if ($this->command)
            {
            	$this->command->info($text);
            }
        }

        protected function saveLogs()
        {
            file_put_contents($this->paths->storage('install.log'), json_encode($this->logs, JSON_PRETTY_PRINT));
        }

}

class Log
{

    public $state;
    public $title;
    public $text;

    function __construct($state, $title, $text)
    {
        $this->state = $state;
        $this->title = $title;
        $this->text  = $text;
    }
}
