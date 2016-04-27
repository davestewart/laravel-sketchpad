<?php namespace davestewart\sketchpad\providers;

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
	 * Bootstrap the    pplication services.
	 *
	 * @param Sketchpad $sketchpad
	 */
	public function boot(Sketchpad $sketchpad)
	{
		// variables
		$root       = realpath(__DIR__ . '/../../') . '/';
		$resources  = $root . 'resources/';
		$config     = $resources . 'config/config.php';

		// vendor folders
		$this->loadViewsFrom($resources . 'views', 'sketchpad');
		$this->mergeConfigFrom($config, 'sketchpad');

		// publishes
		$this->publishes
		([
			$config => config_path('sketchpad.php'),
			$root . 'public' => public_path('vendor/sketchpad'),
		]);

		// initialize class
		$sketchpad->init();
	}

}