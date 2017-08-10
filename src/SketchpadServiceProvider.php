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

	public static $settings = [];

	protected $middleware = [
		'web'
	];

	/**
	 * Custom permissions
	 *
	 * @param $name
	 * @param $value
	 */
	public static function set ($name, $value)
	{
		array_set(self::$settings, $name, $value);
	}

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
			$views          = $root . 'package/views';
            $config         = app(SketchpadConfig::class);


        // ------------------------------------------------------------------------------------------------
		// views

			$this->loadViewsFrom($views, 'sketchpad');
            $this->loadViewsFrom(base_path($config->views), 'sketchpad');
			view()->composer('*', function ($view) use ($config) {
				$view->with('route', $config->route);
			});


        // ------------------------------------------------------------------------------------------------
		// routes

			$parameters =
			[
				'namespace'     => 'davestewart\sketchpad\controllers',
				'middleware'    => $this->middleware,
			];

			Route::group($parameters, function ($router) use ($config)
			{
				require (__DIR__ . '/routes.php');
			});

	}

}