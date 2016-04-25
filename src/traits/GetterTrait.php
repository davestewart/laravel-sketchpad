<?php namespace davestewart\doodle\traits;

/**
 * Class GetterTrait
 *
 * @package davestewart\doodle\traits
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