<?php namespace davestewart\sketchpad\traits;

use davestewart\sketchpad\objects\reflection\Comment;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class ReflectionTraits
 *
 * @package davestewart\sketchpad\traits
 */
trait ReflectionTraits
{

	// ------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * The Reflection class for this object
		 *
		 * @var ReflectionClass|ReflectionMethod
		 */
		protected $ref;

		/**
		 * The DocComment of the element
		 *
		 * @var Comment
		 */
		public $comment;

		/**
		 * A friendly label
		 *
		 * @var string
		 */
		public $label;


	// ------------------------------------------------------------------------------------------------
	// METHODS

		/**
		 * Common function for reflection classes to grab first paragraph of doc comments
		 *
		 * @return  Comment
		 */
		public function getDocComment()
		{
			return new Comment($this->ref->getDocComment());
		}

		/**
		 * Determines the label for the element
		 *
		 * Returns a @label parameter, if available, otherwise, humanizes the element name
		 *
		 * @param null $default
		 * @return null|string
		 */
		public function getLabel($default = null)
		{
			$label  = $this->getTag('label');
			if( ! $label )
			{
				$label  = $default ?: $this->ref->getName();
				$label  = preg_replace('/^(.+)Controller$/', '$1', $label);
				//$label  = preg_replace('/_/', ' ', $label);
				//$label  = preg_replace('/([a-z])([A-Z0-9])/', '$1 $2', $label);
				//$label  = strtolower($label);
			}
			return $label;
		}

		/**
		 * Gets the first available value of a tag
		 *
		 * @param   string  $name
		 * @return  string|null
		 */
		public function getTag($name)
		{
			$comment = $this->ref->getDocComment();
			preg_match('/@' .$name. '\s+(.+)/', $comment, $matches);
			return $matches ? $matches[1] : null;
		}


}