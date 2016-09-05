<?php namespace davestewart\sketchpad\utils;

/**
 * Options class
 *
 * ${CARET}
 */
class Options
{

	// -----------------------------------------------------------------------------------------------------------------
	// properties

		public $options;


	// -----------------------------------------------------------------------------------------------------------------
	// instantiation

		public function __construct($options = '')
		{
			$this->options = $this->parse($options);
		}



	// ------------------------------------------------------------------------------------------------
	// methods

		public function has($name)
		{
			return array_key_exists($name, $this->options);
		}

		public function get($name, $default = null)
		{
			return array_key_exists($name, $this->options)
				? $this->options[$name]
				: $default;
		}

		public function set($name, $value)
		{
			$this->options[$name] = $value;
			return $this;
		}

		public function __get($name)
		{
			return $this->get($name);
		}

		public function __set($name, $value)
		{
			$this->set($name, $value);
		}

	// -----------------------------------------------------------------------------------------------------------------
	// utilties

		/**
		 * Converts string options to a hash
		 *
		 * Operation:
		 *
		 * - splits options by |
		 * - splits arguments by :
		 * - splits argument members by ,
		 *
		 * Example:
		 *
		 *  index|html:path|pre:path,methods
		 *
		 * @param $input
		 * @return array
		 */
		public function parse($input)
		{
			$output = [];
			$options = explode('|', $input);
			foreach($options as $option)
			{
				$name = $option;
				$value = 1;
				if(strpos($option, ':') !== false)
				{
					list($name, $value) = explode(':', $option, 2);
				}
				$output[$name] = strstr($value, ',') !== false
					? explode(',', $value)
					: $value;
			}
			return $output;
		}


}