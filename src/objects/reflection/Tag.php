<?php namespace davestewart\sketchpad\objects\reflection;

/**
 * Sketchpad Parameter
 * 
 * Represents a method parameter
 */
 
class Tag
{

	// ---------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * The tag name
		 * 
		 * @var string
		 */
		public $name;

		/**
		 * The tag type
		 *
		 * @var string
		 */
		public $type;

		/**
		 * The tag text
		 *
		 * @var string
		 */
		public $text;


	// ---------------------------------------------------------------------------------------------------------------
	// METHODS

		/**
		 * @param      $name
		 * @param      $text
		 */
		public function __construct($name, $text)
		{
			// basic
			$this->name         = $name;
			$this->text         = $text;

			// param
			if($name === 'param')
			{
				preg_match('/(\w+)\s+\$(\w+)(.*)/', $text, $matches);
				if($matches)
				{
					$this->type     = $matches[1];
					$this->name     = $matches[2];
					$this->text     = trim($matches[3]);
				}
			}

		}

}

