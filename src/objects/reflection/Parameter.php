<?php namespace davestewart\sketchpad\objects\reflection;

use JsonSerializable;
use ReflectionParameter;

/**
 * Sketchpad Parameter
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
		 * @var mixed
		 */
		public $value;

		/**
		 * Optional docblock text (must be set externally)
		 *
		 * @var string
		 */
		public $text;


	// ---------------------------------------------------------------------------------------------------------------
	// METHODS

		/**
		 * Parses a ReflectionParameter into a more usable object
		 *
		 * @param   ReflectionParameter     $param
		 * @param   string                  $text
		 */
		public function __construct($param, $text = '')
		{
			// name & optional
			$this->name         = $param->getName();
			$this->optional     = $param->isOptional();
			$this->text         = $text;

			// value
			$value              = $param->isOptional() && ! $param->isVariadic()
                                    ? $param->getDefaultValue()
                                    : $param->getName();
            $value              = $value === null ? 'null' : $value;
			$value              = $value === false ? 'false' : $value;
			$this->value	    = $value;

			// type
			$this->type         = $this->getType($param, $value);
		}

		/**
		 * Gets the parameter type
		 *
		 * @param ReflectionParameter $param
		 * @return string
		 */
		protected function getType($param, $value)
		{
			$type = method_exists($param, 'getType')
				? $param->getType()
				: gettype($value);

			if($type == 'double' || $type == 'integer' || $type == 'int')
			{
				$type = 'number';
			}

			return $type;
		}

		/**
		 * Specify data which should be serialized to JSON
		 */
		public function jsonSerialize()
		{
			$data           = (object) [];
			$data->name     = $this->name;
			$data->type     = $this->type;
			$data->value    = $this->value;
			$data->text     = $this->text;
			return $data;
		}

}

