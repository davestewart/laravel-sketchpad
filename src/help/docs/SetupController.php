<?php namespace davestewart\sketchpad\help\docs;

use davestewart\sketchpad\config\Paths;
use davestewart\sketchpad\config\SketchpadConfig;

/**
 * Learn how to set up Sketchpad to supercharge your productivity
 *
 * @order 1
 */
class SetupController
{

	public function index()
	{
		md('sketchpad::help/setup/index');
	}

	/**
	 * Add controller folder paths to load and run controllers and their methods
	 *
	 * @group Resources
	 */
	public function controllers(SketchpadConfig $config)
	{
		$paths = $config->settings->get('paths.controllers');
		return view('sketchpad::help/setup/controllers', compact('paths'));
	}

	/**
	 * Load views, including Markdown and Vue, from a custom folder
	 */
	public function views(SketchpadConfig $config)
	{
		$views = $config->settings->get('paths.views');
		echo md('sketchpad::help/setup/views', ['views' => $views]);
	}

	/**
	 * Load user scripts and styles to augment your Sketchpad development
	 */
	public function assets(SketchpadConfig $config)
	{
		$assets = $config->settings->get('paths.assets');
		$views = $config->settings->get('paths.views');
		echo md('sketchpad::help/setup/assets', compact('assets', 'views'));
	}

	/**
	 * Set up and configure Sketchpad Reload to enable live-reloading and live-coding
	 */
	public function liveReload()
	{
		alert('LiveReload enabled', true);
		alert('LiveReload not running!', false);
		echo md('sketchpad::help/setup/livereload');
	}

	/**
	 * Customise various aspects of Sketchpad to make your setup more relevant to visitors
	 *
	 * @group Customisation
	 */
	public function settings(SketchpadConfig $config)
	{
		$views = $config->settings->get('paths.views');
		echo md('sketchpad::help/setup/settings', compact('views'));
	}

	/**
	 * Replace top-level Sketchpad pages with your own custom content
	 */
	public function pages(SketchpadConfig $config)
	{
		$views  = $config->settings->get('paths.views');
		echo md('sketchpad::help/setup/pages', compact('views'));
	}

	/**
	 * Add 3rd-party libraries, tracking or other head content
	 */
	public function head(Paths $paths, SketchpadConfig $config)
	{
		$head = $config->getView('head');
		$path = $paths->package('setup/views/head.blade.php');
		$html = file_get_contents($path);
		echo md('sketchpad::help/setup/head', compact('head', 'html'));
	}

	/**
	 * Configure the admin file to provide guest usage and prevent changes to settings
	 *
	 * @group Admin
	 */
	public function permissions()
	{
		echo md('sketchpad::help/setup/permissions');
	}

}
