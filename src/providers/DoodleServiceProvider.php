<?php namespace davestewart\sketchpad\providers;

use davestewart\sketchpad\services\SketchpadService;
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
		$this->app->singleton(SketchpadService::class);
	}

	/**
	 * Bootstrap the    pplication services.
	 *
	 * @param SketchpadService $sketchpad
	 */
	public function boot(SketchpadService $sketchpad)
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