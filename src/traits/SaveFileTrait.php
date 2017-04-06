<?php namespace davestewart\sketchpad\traits;

/**
 * FilesystemTrait class
 */
trait SaveFileTrait
{
	/**
	 * Saves content to a file, updating file permissions to ensure a save
	 *
	 * @param   string  $file
	 * @param   string  $data
	 */
	protected function _save($file, $data)
	{
		// variables
		$folder = dirname($file);

		// ensure folder exists
		if(!file_exists($folder))
		{
			mkdir($folder, 0777, true);
		}

		// ensure folder is writable
		else if(!is_writable($folder))
		{
			chmod($folder, 0777);
		}

		// write file
		if (file_exists($file) && !is_writable($file))
		{
			chmod($file, 0644);
		}
		file_put_contents($file, $data);
	}
}
