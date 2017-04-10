<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\objects\file\File;
use davestewart\sketchpad\objects\reflection\ControllerError;
use davestewart\sketchpad\objects\route\ControllerReference;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class ControllerObject
 *
 * @private
 */
class Controller extends File implements Arrayable, JsonSerializable
{
	
	use \davestewart\sketchpad\traits\ReflectionTraits;
	
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

		public static function fromPath($path, $route = '', $process = true)
		{
			// check file exists
			if(!file_exists($path))
			{
				return new ControllerError($path, $route, 'File does not exist');
			}

			// class
			try
			{
				return new self($path, $route, $process);
			}

			// error
			catch(\Exception $error)
			{
				return new ControllerError($path, $route, 'Invalid class; check file name and class name');
			}

		}
	
		public function __construct($path, $route = '', $process = true)
		{
			// parent
			parent::__construct($path, $route);

			// objects
			$class              = getClassPath($path);
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
			foreach ($methods as $m)
			{
				if($m->getFileName() === $file && $m->name !== '__construct')
				{
					$method = new Method($m, $this->route);
					if( ! isset($method->comment->tags['private']) )
					{
						$arr[] = $method;
					}
				}
			}
			$this->methods = $arr;

			// return
			return $this;
		}

		public function getReference()
		{
			return new ControllerReference($this->route, $this->path, $this->classpath);
		}

		public function isValid()
		{
			return !$this->ref->isAbstract()
				&& !$this->getTag('private')
				&& count($this->methods) > 0;
		}

		public function toArray()
		{
			$data =
			[
				'type'      => 'controller',
				'class'     => $this->classname,
				'path'      => str_replace(base_path() . '/', '', $this->path),
				'name'      => $this->name,
				'route'     => $this->route,
				'folder'    => preg_replace('%[^/]+$%', '', $this->route),
				'label'     => $this->label,
				'comment'   => $this->comment,
				'methods'   => $this->methods,
			];
			return $data;
		}

		function jsonSerialize()
		{
			return $this->toArray();
		}

}

/**
 * Parses the class source to build a FQ class path
 *
 * @param $path
 * @return string
 */
function getClassPath($path)
{
	$file = file_get_contents($path);
	preg_match('/namespace\s+([\w\\\\]+)/', $file, $namespace);
	preg_match('/class\s+(\w+)/', $file, $class);
	if($namespace && $class)
	{
		return $namespace[1] . '\\' . $class[1];
	}
}
