<?php namespace davestewart\sketchpad;

use davestewart\sketchpad\config\Paths;
use davestewart\sketchpad\config\SketchpadConfig;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Support\ServiceProvider;
use Route;

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
	    // singletons
		$this->app->singleton(Paths::class);
		$this->app->singleton(SketchpadConfig::class);
		$this->app->singleton(Sketchpad::class);

		// install command
        $this->commands([
            'davestewart\sketchpad\commands\InstallCommand'
        ]);
	}

	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{

		// ------------------------------------------------------------------------------------------------
		// variables

			$root           = realpath(__DIR__ . '/../') . '/';
			$views          = $root . 'resources/views';
            $config         = app(SketchpadConfig::class);


        // ------------------------------------------------------------------------------------------------
		// views

			$this->loadViewsFrom($views, 'sketchpad');
            $this->loadViewsFrom(base_path($config->views), 'sketchpad');


        // ------------------------------------------------------------------------------------------------
		// routes

			// middleware
			$parameters =
			[
				'namespace'     => 'davestewart\sketchpad\controllers'
			];

			// routes
			Route::group($parameters, function ($router) use ($config)
			{
				// api - controllers
				Route::post ($config->route . 'api/run/{params?}',  'ApiController@run')->where('params', '.*');
				Route::get  ($config->route . 'api/load/{path?}',   'ApiController@load')->where('path', '.*');

				// api - settings
				Route::get  ($config->route . 'api/settings',       'ApiController@settings');
				Route::post ($config->route . 'api/settings',       'ApiController@settings');

				// api - tools
				Route::get ($config->route .  'api/path',           'ApiController@path');
				Route::post ($config->route . 'api/create',         'ApiController@create');

				// setup
				Route::get  ($config->route . 'setup',              'SetupController@index');
				Route::get  ($config->route . 'setup/install',      'SetupController@install');
				Route::post ($config->route . 'setup/install',      'SetupController@submit');
				Route::get  ($config->route . 'setup/test',         'SetupController@test');

				// assets
				Route::get  ($config->route . 'assets/user/{file}', 'SketchpadController@userAsset')->where(['file' => '.*']);
				Route::get  ($config->route . 'assets/{file}',      'SketchpadController@asset')->where(['file' => '.*']);

				// sketchpad
				Route::get  ($config->route . '{params?}',          'SketchpadController@index')->where('params', '.*');
			});

	}

}