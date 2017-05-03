<?php namespace davestewart\sketchpad\utils;

/**
 * Utility class to covert to and from string based options, similar to Laravel validation
 *
 * See parse() method for more information
 */
class Options
{

	// -----------------------------------------------------------------------------------------------------------------
	// properties

		public $options;


	// -----------------------------------------------------------------------------------------------------------------
	// instantiation

		public function __construct($str = '')
		{
			$this->options = $this->parse($str);
		}

		public static function create ($str = '')
		{
			return new Options($str);
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
		 * - splits argument member names and values by =
		 *
		 * Example:
		 *
		 *  index|html:path|pre:path,methods|values:One=1,Two=2,Three=3
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
				if (strstr($value, ',') !== false)
				{
					$values = explode(',', $value);
					if (strstr($value, '=') !== false)
					{
						$pairs = [];
						foreach($values as $value)
						{
							list($n, $v) = explode('=', $value, 2);
							$pairs[$n] = $v;
						}
						$values = $pairs;
					}
					$value = $values;
				}
				$output[$name] = $value;
			}
			return $output;
		}


}