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
			    include 'utils/routes.php';
			});

	}

}