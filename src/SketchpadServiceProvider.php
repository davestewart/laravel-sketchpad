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
			    include 'utils/routes.php';
			});

	}

}