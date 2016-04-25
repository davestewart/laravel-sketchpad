<?php namespace davestewart\doodle\objects\reflection;

use davestewart\doodle\traits\ReflectionTraits;
use JsonSerializable;

/**
 * Reflection Method
 * 
 * Represents a PHP Method, storing method parameters and a few extra properties
 */
 
class Method implements JsonSerializable
{
	
	use ReflectionTraits;
	
	// ------------------------------------------------------------------------------------------------
	// PROPERTIES

		/** @var string */
		public $route;
	
		/** @var string */
		public $name;
	
		/** @var Parameter[] */
		public $params;
	
		/** @var string */
		public $signature;
	

	// ------------------------------------------------------------------------------------------------
	// METHODS
	
		/**
		 * Class constructor
		 *
		 * @param \ReflectionMethod $method
		 */
		public function __construct($method, $route)
		{
			// properties
			$this->ref      = $method;
			$this->name		= $method->name;
			$this->route    = $route . $this->name . '/';
			$this->label	= $this->getLabel();
			$this->comment	= $this->getDocComment();

			// params
			$params			= $method->getParameters();
			$this->params	= [];
			foreach($params as $param)
			{
				array_push($this->params, new Parameter($param));
			}
		}
	
		public function toArray($simple = false)
		{
			// base
			$data               = (object) [];
			$data->name         = $this->name;
			$data->route        = $this->route;
			$data->signature    = $this->signature;

			// complex
			if( ! $simple )
			{
				$data->comment      = $this->comment;
				$data->params       = $this->params;
			}

			// return
			return $data;
		}

		function jsonSerialize()
		{
			return $this->toArray();
		}

}

