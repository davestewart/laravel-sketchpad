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
		$resources = realpath(__DIR__ . '/../../') . '/resources/';

		// vendor folders
		$this->loadViewsFrom($resources . 'views', 'sketchpad');
		$this->mergeConfigFrom($resources . 'config/config.php', 'sketchpad');

		// publishes
		$this->publishes
		([
			$resources . 'public' => public_path('vendor/sketchpad'),
		]);

		// initialize class
		$sketchpad->init();
	}

}