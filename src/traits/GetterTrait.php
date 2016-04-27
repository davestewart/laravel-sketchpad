<?php namespace davestewart\sketchpad\traits;

/**
 * Class GetterTrait
 *
 * @package davestewart\sketchpad\traits
 */
trait GetterTrait
{
	public function __get($name)
	{
		if(property_exists($this, $name))
		{
			return $this->$name;
		}
	}
}