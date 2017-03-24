<?php namespace davestewart\sketchpad\objects\scanners;

/**
 * Class ControllerFolderScanner
 *
 * @property string $path
 */
class AbstractScanner
{

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		
		protected $path;

	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS

		/**
		 * Utility function to return a folder path with a slash at the end
		 *
		 * @param   string $path The path to cap with a "/"
		 * @return string
		 */
		static public function folderize($path)
		{
			return rtrim($path, '/') . '/';
		}

		protected function isController($path)
		{
			return is_file($path) && preg_match('/Controller.php$/', $path);
		}

}