<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\utils\Options;

/**
 * Sketchpad Parameter
 * 
 * Represents a method parameter
 */
 
class Field extends Tag
{

	public $attrs;

	/**
	 * @param      $name
	 * @param      $text
	 */
	public function __construct($name, $text)
	{
		parent::__construct($name, $text);
		$this->attrs = $this->text !== ''
			? Options::create($this->text)->options
			: null;
		unset($this->text);
	}

}

