<?php namespace davestewart\doodle\objects\file;
use davestewart\doodle\services\DoodleService;

/**
 * Doodle File object
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
		public $filename;

		/**
		 * The full path to the file
		 *
		 * @var string
		 */
		public $filepath;

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
		 */
		public function __construct($filepath)
		{
			$segments       = explode('/', trim($filepath, '/'));
			$this->filename = array_pop($segments);
			$this->filepath = $filepath;

			/** @var DoodleService $doodle */
			$doodle         = app(DoodleService::class);
			$this->route    = $doodle->routeFromPath($filepath);
		}
		
	}


