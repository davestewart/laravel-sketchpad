<?php namespace davestewart\doodle\objects\file;
use davestewart\doodle\objects\reflection\Controller;
use davestewart\doodle\services\DoodleService;
use JsonSerializable;

/**
 * Doodle Folder
 * 
 * Represents a controller folder, storing subfolders, controllers
 */
 
class Folder extends File implements JsonSerializable
{

	// ---------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * An array of sequential parent folders / routes
		 *
		 * @var string[]
		 */
		public $parents;

		/**
		 * An array of subfolders within this folder
		 *
		 * @var Folder[]
		 */
		public $folders;

		/**
		 * An array of controllers within this folder
		 *
		 * @var Controller[]
		 */
		public $controllers;


	// ---------------------------------------------------------------------------------------------------------------
	// METHODS

		/**
		 * Class constructor
		 *
		 * @param   string $path      The absolute path to the folder object
		 * @param   bool   $recursive An optional flag to recursively process child folders
		 */
		public function __construct($path, $recursive = false)
		{
			// parent
			parent::__construct($path);

			// properties
			$this->path         = rtrim($this->path, '/') . '/';
			$this->folders      = [];
			$this->controllers  = [];

			// get parent routes
			$this->setParents();

			// get members
			$this->process($recursive);
		}

		/**
		 * Process contained folders and controllers
		 *
		 * @param   bool    $recursive      An optional flag to recursively get child folders
		 */
		public function process($recursive = false)
		{
			// reset
			$this->folders      = [];
			$this->controllers  = [];
			
			// variables
			$files = array_diff(scandir($this->path), ['.', '..']);

			// loop
			foreach ($files as $file)
			{
				$path = $this->path . $file;
				if(is_dir($path))
				{
					$this->folders[] = new Folder($path, $recursive);
				}
				else if(is_file($path) && preg_match('/Controller.php$/', $path))
				{
					$this->controllers[] = new Controller($path);
				}
			}
		}

		/**
		 * Build list of parent folders / routes
		 */
		protected function setParents()
		{
			// variables
			$segments   = explode('/', trim($this->route, '/'));
			$path       = '';

			// build parent routes
			foreach($segments as $segment)
			{
				$path .= $segment . '/';
				$this->parents[$segment] = $path;
			}

			// remove last parent
			array_pop($this->parents);
		}
	
		/**
		 * Specify data which should be serialized to JSON
		 */
		public function jsonSerialize()
		{
			$data               = (object) [];
			$data->type         = 'folder';
			$data->name         = $this->name;
			$data->route        = $this->route;
			$data->parents      = $this->parents;
			$data->folders      = $this->folders;
			$data->controllers  = $this->controllers;
			return $data;
		}

}
