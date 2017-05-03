<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\utils\Options;
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

		/**
		 * Any field attribute values
		 *
		 * @var Field
		 */
		public $field;

		/**
		 * Any field attribute values
		 *
		 * @var array
		 */
		public $attrs;


	// ---------------------------------------------------------------------------------------------------------------
	// METHODS

		/**
		 * Parses a ReflectionParameter into a more usable object
		 *
		 * @param   ReflectionParameter $param      PHP reflection parameter object
		 * @param   Tag                 $tag        An optional PHP DocComment @param declaration of user-defined type and text
		 */
		public function __construct($param, $tag = null)
		{
			// name & optional
			$this->name         = $param->getName();
			$this->optional     = $param->isOptional();
			$this->text         = $tag ? $tag->text : '';

			// value
			$value              = $param->isOptional() && ! $param->isVariadic()
                                    ? $param->getDefaultValue()
                                    : $param->getName();

            //$value              = $value === null ? 'null' : $value;
			//$value              = $value === false ? 'false' : $value;
			$this->value	    = $value;

			// type
			$this->type         = $this->getType($param, $value, $tag ? $tag->type : null);
		}

		/**
		 * Set the field value
		 *
		 * @param   Field   $field
		 * @return          $this
		 */
		public function setField ($field)
		{
			$this->field = $field;
			return $this;
		}

		/**
		 * Gets the parameter type
		 *
		 * @param   ReflectionParameter $param
		 * @param   mixed               $value
		 * @param   string              $type
		 * @return  string
		 */
		protected function getType($param, $value, $type = null)
		{
			// attempt to get the type
			$type = method_exists($param, 'getType')
				? $param->getType()
				: $type
					? $type
					: gettype($value);

			// coerce type to something javascript will understand
			if($type == 'double' || $type == 'float' || $type == 'integer' || $type == 'int')
			{
				$type = 'number';
			}
			else if($type == 'bool')
			{
				$type = 'boolean';
			}
			else if($type == 'NULL')
			{
				$type = 'null';
			}

			// return
			return $type;
		}

		/**
		 * Specify data which should be serialized to JSON
		 */
		public function jsonSerialize()
		{
			// base values
			$values = (object)
			[
				'name'      => $this->name,
				'type'      => $this->type,
				'value'     => $this->value,
				'text'      => $this->text,
				'field'     => null,
				'attrs'     => (object) [],
			];

			// override if field set
			if (isset($this->field))
			{
				$values->field = $this->field->type;
				$values->attrs = (object) $this->field->attrs;

			}
			return $values;
		}

}

