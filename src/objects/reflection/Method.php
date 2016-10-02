<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\traits\ReflectionTraits;
use JsonSerializable;
use ReflectionParameter;

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
			$this->route    = strtolower($route . $this->name);
			$this->label	= $this->getLabel();
			$this->comment	= $this->getDocComment();

			// params
			$params			= $method->getParameters();
			$this->params	= [];
			foreach($params as /** @var ReflectionParameter */ $p)
			{
				if($p->isOptional())
				{
					$tag    = $this->comment->getParam($p->name);
					$param  = new Parameter($p, $tag);
					array_push($this->params, $param);
				}
			}
		}
	
		public function toArray($simple = false)
		{
			// base
			$data               = (object) [];
			$data->name         = $this->name;
			$data->label        = $this->label;
			$data->route        = $this->route;
			//$data->signature    = $this->signature;
			$data->params       = $this->params;
			$data->comment      = $this->comment;
			$data->tags         = $this->comment->tags;
			$data->error        = 0;

			// return
			return $data;
		}

		function jsonSerialize()
		{
			return $this->toArray();
		}

}

