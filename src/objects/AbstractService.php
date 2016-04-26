<?php namespace davestewart\doodle\objects;

/**
 * Class DoodleConfig
 *
 * @package davestewart\doodle\objects
 */
class AbstractService
{

	/**
	 * The app-relative physical file path to the controllers folder
	 *
	 * @var
	 */
	public $path;

	/**
	 * The root relative route to the doodles folder
	 *
	 * @var
	 */
	public $route;


	// ------------------------------------------------------------------------------------------------
	// UTILITIES

	/**
	 * Utility function to return a folder path with a slash at the end
	 *
	 * @param   string $path The path to cap with a "/"
	 * @return  string
	 */
	protected function folderize($path)
	{
		return rtrim($path, '/') . '/';
	}

}


