<?php namespace davestewart\doodle\objects\reflection;

use davestewart\doodle\objects\file\File;

use davestewart\doodle\services\DoodleService;
use JsonSerializable;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class ControllerObject
 *
 * @package davestewart\doodle\helpers
 */
class Controller extends File implements JsonSerializable
{
	
	use \davestewart\doodle\traits\ReflectionTraits;
	
	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * The short class name of the class
		 * 
		 * @var string
		 */
		public $classname;

		/**
		 * The fully-qualified class name
		 * 
		 * @var string
		 */
		public $classpath;

		/**
		 * An array of public Methods of the class
		 * 
		 * @var Method[]
		 */
		public $methods;
	

	// -----------------------------------------------------------------------------------------------------------------
	// METHODS
	
		public static function fromClass($classname)
		{
			$ref    = new ReflectionClass($classname);
			$file   = $ref->getFileName();
			return new self($file);
		}

		public function __construct($path, $process = false)
		{
			// parent
			parent::__construct($path);

			// class
			$class              = ucfirst(str_replace('/', '\\', str_replace('.php', '', str_replace(base_path() . '/', '', $path))));
			$this->ref          = new ReflectionClass($class);
	
			// properties
			$this->classname    = $this->ref->getShortName();
			$this->classpath    = $this->ref->getName();
			$this->label        = $this->getLabel($this->classname);
			$this->comment      = $this->getDocComment();
			$this->methods      = [];

			// methods
			if($process)
			{
				$this->process();
			}
		}

		public function process()
		{
			// variables
			$file       = $this->ref->getFileName();
			$methods    = $this->ref->getMethods(ReflectionMethod::IS_PUBLIC);
			$arr        = [];

			// get methods
			foreach ($methods as $method)
			{
				if($method->getFileName() === $file && $method->name !== '__construct')
				{
					//echo $this->ref->name ."\n";
					//echo $method->class ."\n";
					$arr[] = new Method($method, $this->route);
				}
			}
			$this->methods = $arr;

			// return
			return $this;
		}

		public function toArray($simple = false)
		{
			// base
			$data               = (object) [];

			// data
			if($simple)
			{
				$data->name         = $this->classpath;
				$data->methods      = array_map(function($method) { return $method->name; }, $this->methods);;
			}
			else
			{
				$data->type         = 'controller';
				$data->route        = $this->route;
				$data->label        = $this->label;
				$data->comment      = $this->comment;
				$data->methods      = $this->methods;
			}

			// return
			return $data;
		}

		function jsonSerialize()
		{
			return $this->toArray();
		}

}
