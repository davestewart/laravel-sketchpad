<?php namespace davestewart\sketchpad;

use davestewart\sketchpad\objects\SketchpadConfig;
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
            $publish        = $root . 'publish/';


        // ------------------------------------------------------------------------------------------------
        // config

            $this->mergeConfigFrom($publish . 'config/config.php', 'sketchpad');
            $config         = new SketchpadConfig();


        // ------------------------------------------------------------------------------------------------
		// views

			$this->loadViewsFrom($views, 'sketchpad');
            $this->loadViewsFrom(base_path($config->views), 'sketchpad');


        // ------------------------------------------------------------------------------------------------
		// routes

			// middleware
			$parameters =
			[
				'namespace'     => 'davestewart\sketchpad\controllers',
				'middleware'    => $config->middleware,
			];

            //dd($config);

			// routes
			Route::group($parameters, function ($router) use ($config)
			{
			    // setup view
				Route::get  ($config->route . ':setup', 'SetupController@index');

                // post setup data
				Route::post ($config->route . ':setup', 'SetupController@submit');

                // test setup has worked correctly
				Route::get  ($config->route . ':setup/test', 'SetupController@test');

                // create a new sketchpad controller
				Route::post ($config->route . ':create', 'SketchpadController@create');

                // catch-all command
				Route::get  ($config->route . ':{command}/{data?}', 'SketchpadController@command')->where('data', '.*');

                // catch-call route
				Route::match(['GET', 'POST'], $config->route . '{params?}', 'SketchpadController@call')->where('params', '.*');
			});

	}

}