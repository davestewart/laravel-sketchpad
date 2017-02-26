<?php namespace davestewart\sketchpad\objects\file;

/**
 * Sketchpad File object
 * 
 * Base class that represents a filesystem object such as a folder or controller
 */
 
class File
{

	// ---------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * The name component of the file
		 *
		 * @var string
		 */
		public $name;

		/**
		 * The full path to the file
		 *
		 * @var string
		 */
		public $path;

		/**
		 * The URL route to this filesystem object
		 *
		 * @var string
		 */
		public $route;


	// ---------------------------------------------------------------------------------------------------------------
	// INITIALIZE

		/**
		 * Class constructor
		 *
		 * @param   string  $path
		 * @param   string  $route
		 */
		public function __construct($path, $route = '')
		{
			$segments       = explode('/', trim($path, '/'));
			$this->name     = array_pop($segments);
			$this->path     = $path;
			$this->route    = $route;
		}

		public function exists()
		{
			return file_exists($this->path);
		}
		
	}


