<?php namespace davestewart\doodle\providers;

use davestewart\doodle\services\DoodleService;
use Illuminate\Support\ServiceProvider;

/**
 * DoodleServiceProvider
 *
 * Sets up the doodle package
 */
class DoodleServiceProvider extends ServiceProvider
{

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(DoodleService::class);
	}

	/**
	 * Bootstrap the    pplication services.
	 *
	 * @param DoodleService $doodle
	 */
	public function boot(DoodleService $doodle)
	{
		// variables
		$resources = realpath(__DIR__ . '/../../') . '/resources/';

		// vendor folders
		$this->loadViewsFrom($resources . 'views', 'doodle');
		$this->mergeConfigFrom($resources . 'config/config.php', 'doodle');

		// publishes
		$this->publishes
		([
			$resources . 'public' => public_path('vendor/doodle'),
		]);

		// initialize class
		$doodle->init();
	}

}