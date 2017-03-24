<?php namespace davestewart\sketchpad\objects\scanners;

use davestewart\sketchpad\traits\GetterTrait;

/**
 * Class ControllerFolderScanner
 *
 * @property string $namespace
 */
class Finder extends AbstractScanner
{

	use GetterTrait;

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		protected $namespace;


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS

		public function start()
		{
			$path = app_path('Http/Controllers/Controller.php');
			if( ! $this->isController($path) )
			{
				$path = $this->scan(app_path());
			}

			if($path)
			{
				$this->path         = preg_replace('%/[^/\\\\]+$%', '/', $path);
				$this->namespace    = $this->getNamespace($path);
				return true;
			}

			return false;
		}
	
		protected function scan($path)
		{
			// variables
			$path       = AbstractScanner::folderize($path);
			$files      = array_diff(scandir($path), ['.', '..']);
			$folders    = [];

			// loop, and process files first
			foreach ($files as $file)
			{
				$f = $path . $file;
				if(is_dir($f))
				{
					$folders[] = $f;
				}
				else if($this->isController($f))
				{
					return $f;
				}
			}

			// folders
			foreach($folders as $f)
			{
				$result = $this->scan($f);
				if($result)
				{
					return $result;
				}
			}
		}

		protected function getNamespace($path)
		{
			$file = file_get_contents($path);
			preg_match('/namespace\s+([\w\\\\]+)/', $file, $matches);
			if($matches)
			{
				return $matches[1];
			}
			return null;
		}

}