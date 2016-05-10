<?php namespace davestewart\sketchpad;

use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Support\ServiceProvider;

/**
 * SketchpadServiceProvider
 *
 * Sets up the sketchpad package
 */
class SketchpadServiceProvider extends ServiceProvider
{

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(Sketchpad::class);
	}

	/**
	 * Bootstrap the application services.
	 *
	 * The publishing section is in two parts:
	 *
	 *    1. Config        : this allows the config to be published, then edited, before...
	 *    2. Public assets : the JS and CSS files for the web interface
	 *    3. Controllers   : an example controller to show what the package can do
	 *
	 * @param Sketchpad $sketchpad
	 */
	public function boot(Sketchpad $sketchpad)
	{
		// ------------------------------------------------------------------------------------------------
		// variables

			// roots
			$root           = realpath(__DIR__ . '/../') . '/';
			$resources      = $root . 'resources/';
			$publish        = $root . 'publish/';

			// package assets
			$config         = $publish . 'config/config.php';
			$views          = $resources . 'views';

			// publish paths (attempt to get from config in case publishing was published in two halves)
			$assets         = config('sketchpad.assets', 'vendor/sketchpad');
			$examples       = config('sketchpad.path', 'app/Http/Controllers/Sketchpad');


		// ------------------------------------------------------------------------------------------------
		// vendor folders

			$this->loadViewsFrom($views, 'sketchpad');
			$this->mergeConfigFrom($config, 'sketchpad');


		// ------------------------------------------------------------------------------------------------
		// publish

			// config
			$this->publishes
			([
				$config => config_path('sketchpad.php')
			], 'config');

			$this->publishes
			([
				$publish . 'assets' => public_path($assets),
			], 'assets');

			$this->publishes
			([
				$publish . 'examples' => base_path($examples),
			], 'examples');


		// ------------------------------------------------------------------------------------------------
		// initialize class

			//$sketchpad->init();
	}

}