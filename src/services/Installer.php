<?php namespace davestewart\sketchpad\services;

use davestewart\sketchpad\objects\install\ClassTemplate;
use davestewart\sketchpad\objects\install\Copier;
use davestewart\sketchpad\objects\install\Folder;
use davestewart\sketchpad\objects\install\JSON;
use davestewart\sketchpad\objects\install\Template;
use davestewart\sketchpad\objects\settings\Paths;
use davestewart\sketchpad\objects\settings\InstallerSettings;

/**
 * Installs Sketchpad according to the settings saved by the GUI form
 */
class Installer
{

    // -----------------------------------------------------------------------------------------------------------------
    // PROPERTIES

    // variables

        protected $settings;
        protected $paths;

    // state

        /** @var Log[] An array of installation step results */
        public $logs;

        /** @var bool Flag to indicate installation was a success or failure */
        public $state;

    // installers

        /** @var JSON */
        public $composer;

        /** @var Template $config */
        public $config;

        /** @var Copier */
        public $prefs;

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


    // -----------------------------------------------------------------------------------------------------------------
    // INSTANTIATION

        public function __construct()
        {
            $this->state        = true;
            $this->settings     = new InstallerSettings();
            $this->paths        = new Paths();
            $this->initialize();
        }

        protected function initialize()
        {
            // variables
            $settings           = $this->settings;
            $publish            = $this->paths->publish();

            // update compose
            if($this->settings->autoloader)
            {
                $this->composer = new JSON('composer.json');
            }

            // objects
            //$this->config       = new Template( $publish . 'templates/config.txt', 'custom/config/sketchpad.php');
            $this->config       = new Template( $publish . 'templates/config.txt', config_path('sketchpad.php'));
            $this->prefs        = new Copier(   $publish . 'config/settings.json', storage_path('vendor/sketchpad/'));
            $this->controllers  = new Folder(   $settings->controllers);
            $this->controller   = new ClassTemplate( $publish . 'resources/ExampleController.txt', $settings->controllers . '{filename}.php');
            $this->views        = new Folder(   $settings->views);
            $this->view         = new Copier(   $publish . 'resources/example.blade.php', $settings->views);
            $this->assets       = new Copier(   $publish . 'assets', public_path($settings->assets));

        }


    // ------------------------------------------------------------------------------------------------
    // install

        public function install()
        {
            $this->config
                ->set($this->settings)
                ->create();

            $this->prefs
                ->create();

            if($this->composer)
            {
                $this->composer
                    ->set("autoload.psr-4.{$this->settings->namespace}", "{$this->settings->basedir}")
                    ->create();
            }

            $this->controllers
                ->create();

            $this->controller
                ->setNamespace()
                ->set($this->settings)
                ->create();

            $this->views
                ->create();

            $this->view
                ->create();

            $this->assets
                ->create();
        }

        /**
         * Checks that the installer copied the correct files and made all the right updates
         */
		public function test()
		{
		    $this->logs = [];

		    $this->config->exists()
                ? $this->pass('Config created')
                : $this->fail('The sketchpad config file could not be found', "Copy it from <code>{$this->config->src}</code>");

		    $this->prefs->exists()
                ? $this->pass('Settings created')
                : $this->fail('The sketchpad settings file could not be found', "Copy it from <code>{$this->prefs->src}</code>");

            if($this->composer)
            {
                $key    = $this->settings->namespace;
                $value  = $this->settings->basedir;
                $this->composer->has('autoload.psr-4.' . $key)
                    ? $this->pass('Composer autoload config updated')
                    : $this->fail('The PSR-4 autoload entry was not added to your composer.json', "Add a new entry in <code>autoload.psr-4</code>: <code>\"$key\" : \"$value\"</code>");
            }

            $this->controllers->exists()
                ? $this->pass('Controller folder created')
                : $this->fail('The sketchpad controllers folder was not found', "Create it at <code>{$this->controllers->src}</code>");

            $this->controller->exists()
                ? $this->pass('Example controller created')
                : $this->fail('The sketchpad example controller could not be found', "Create a new controller in <code>{$this->controller->trg}</code>");

            $this->controller->loads()
                ? $this->pass('Example controller loaded')
                : $this->fail('Unable to load example controller', "Check the namespace declaration in <code>{$this->controller->trg}</code>");

            $this->views->exists()
                ? $this->pass('Views folder created')
                : $this->fail('The sketchpad views folder was not found', "Create it at <code>{$this->views->src}</code>");

            $this->view->exists()
                ? $this->pass('Example view copied')
                : $this->fail('The sketchpad example view was not found', "Create it at <code>{$this->view->trg}</code>");

            $this->assets->exists()
                ? $this->pass('Assets copied')
                : $this->fail('The sketchpad assets folder was not copied', "Copy it from <code>{$this->assets->src}</code>");

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
