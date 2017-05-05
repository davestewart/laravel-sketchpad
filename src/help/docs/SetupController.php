<?php namespace davestewart\sketchpad\help\docs;

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
	 * Customise Sketchpad with user scripts and styles
	 */
	public function assets(SketchpadConfig $config)
	{
		$assets = $config->settings->get('paths.assets');
		echo md('sketchpad::help/setup/assets', compact('assets'));
	}

	/**
	 * Set up and configure Sketchpad Reload to enable live-reloading and live-coding
	 *
	 * @group Admin
	 */
	public function liveReload()
	{
		alert('LiveReload enabled', true);
		alert('LiveReload not running!', false);
		echo md('sketchpad::help/setup/livereload', ['test' => 'fuck you']);
	}

	/**
	 * Customise various aspects of Sketchpad to make your setup more relevant to visitors
	 */
	public function customisation()
	{
		echo md('sketchpad::help/setup/customisation', ['test' => 'fuck you']);
	}

	/**
	 * Configure the admin file to provide guest usage and prevent changes to settings
	 */
	public function permissions()
	{
		echo md('sketchpad::help/setup/permissions', ['test' => 'fuck you']);
	}

}
