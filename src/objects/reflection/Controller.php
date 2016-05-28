<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\objects\file\File;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class ControllerObject
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
	
		public function __construct($path, $route = '', $process = true)
		{
			// parent
			parent::__construct($path, $route);

			// class
			$class              = $this->getClassPath($path);
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

		protected function getClassPath($path)
		{
			$file = file_get_contents($path);
			preg_match('/namespace\s+([\w\\\\]+)/', $file, $namespace);
			preg_match('/class\s+(\w+)/', $file, $class);
			if($namespace && $class)
			{
				return $namespace[1] . '\\' . $class[1];
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

		public function toArray()
		{
			$data =
			[
				'type'      => 'controller',
				'name'      => $this->name,
				'path'      => $this->path,
				'class'     => $this->classname,
				'label'     => $this->label,
				'route'     => $this->route,
				'folder'    => preg_replace('%[^/]+/$%', '', $this->route),
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
