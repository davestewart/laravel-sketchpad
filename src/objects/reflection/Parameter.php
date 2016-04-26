<?php namespace davestewart\doodle\objects\reflection;

use JsonSerializable;
use ReflectionParameter;

/**
 * Doodle Parameter
 * 
 * Represents a method parameter
 */
 
class Parameter extends Tag implements JsonSerializable
{

	// ---------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * The parameter type
		 *
		 * @var mixed|string
		 */
		public $type;

		/**
		 * Whether the parameter is optional or not
		 *
		 * @var bool
		 */
		public $optional;

		/**
		 * The parameter value, if optional
		 *
		 * @var mixed|string
		 */
		public $value;


	// ---------------------------------------------------------------------------------------------------------------
	// METHODS

		/**
		 * Parses a ReflectionParameter into a more usable object
		 *
		 * @param   ReflectionParameter $param
		 */
		public function __construct($param)
		{
			// name & optional
			$this->name         = $param->getName();
			$this->optional     = $param->isOptional();

			// value
			$value              = $param->isOptional()
                                    ? $param->getDefaultValue()
                                    : $param->getName();
            $value              = $value === null ? 'null' : $value;
			$value              = $value === false ? 'false' : $value;
			$this->value	    = $value;

			// type
			$this->type         = $this->getType($param);

		}

		/**
		 * Gets the parameter type
		 *
		 * @param ReflectionParameter $param
		 * @return string
		 */
		protected function getType($param)
		{
			if(method_exists($param, 'getType'))
			{
				return $param->getType();
			}
			return '';
		}

		/**
		 * Specify data which should be serialized to JSON
		 */
		public function jsonSerialize()
		{
			$data           = (object) [];
			$data->name     = $this->name;
			$data->value    = $this->value;
			$data->optional = $this->optional;
			return $data;
		}

}

