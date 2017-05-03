<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\traits\GetterTrait;
use davestewart\sketchpad\utils\Options;
use JsonSerializable;

/**
 * Class Comment
 */
class Comment implements JsonSerializable
{

	use GetterTrait;

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES

		/**
		 * The entire doc comment
		 *
		 * @var string
		 */
		public $comment;

		/**
		 * Any intro paragraph
		 *
		 * @var string
		 */
		public $intro;

		/**
		 * All text excluding the intro
		 *
		 * @var string
		 */
		public $body;

		/**
		 * The method parameter tags
		 *
		 * @var Tag[]
		 */
		public $params;

		/**
		 * Any additional tags
		 *
		 * @var Tag[]
		 */
		public $tags;

		/**
		 * Any user-defined field types
		 *
		 * @var Tag[]
		 */
		public $fields;


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

		public function __construct($body)
		{
			// ------------------------------------------------------------------------------------------------
			// all text content
			
				// grab the entire comment body, trimming any faff
				$body           = trim(preg_replace('%^[\t ]*(/\*\*|\*/|\* ?)%m', "", $body));

				//pd($body);

	
				// strip windows line ends
				$body           = str_replace("\r\n", "\n", $body);
			
	
			// ------------------------------------------------------------------------------------------------
			// paragraph text
			
				// split text by @
				$parts  = explode('@', $body);
				$text   = trim(array_shift($parts));
			
				// got text
				if(strstr($text, '@') === FALSE)
				{
					// split text into "paragraphs" at double-returns
					$blocks = preg_split('/(\n){2,}/', $text, 2);

					// assign text
					$this->intro    = array_shift($blocks) ?: '';
					$this->body     = array_shift($blocks) ?: '';
				}
				
			
			// ------------------------------------------------------------------------------------------------
			// tags
			
				// match all tags, separate of paragraph text
				$this->params   = [];
				$this->tags     = [];

				preg_match_all('/@(\w+)(.*)/', $body, $matches);
				foreach ($matches[0] as $index => $match)
				{
					$name   = $matches[1][$index];
					$value  = trim($matches[2][$index]);
					$tag    = new Tag($name, $value);
					//dump([$name, $value, $tag]);
					if($name === 'param')
					{
						$this->params[$tag->name] = $tag;
					}
					if ($name === 'field')
					{
						$field = new Field($name, $value);
						$this->fields[$field->name] = $field;
					}
					else
					{
						$this->tags[$tag->name] = $tag->text ? $tag->text : true;
					}
				}
				
				// fix favourites
				if(isset($this->tags['favorite']))
				{
					unset($this->tags['favorite']);
					$this->tags['favourite'] = true;
				}
		}

		/**
		 * Returns a parameter by name
		 *
		 * @param   string      $name
		 * @return  Tag|null
		 */
		public function getParam($name)
		{
			return isset($this->params[$name])
				? $this->params[$name]
				: null;
		}

		/**
		 * Returns a tag by name
		 *
		 * @param   string      $name
		 * @return  Tag|null
		 */
		public function getTag($name)
		{
			return isset($this->tags[$name])
				? $this->tags[$name]
				: null;
		}

		/**
		 * Returns a field by name
		 *
		 * @param   string      $name
		 * @return  Field|null
		 */
		public function getField($name)
		{
			return isset($this->fields[$name])
				? $this->fields[$name]
				: null;
		}

		public function jsonSerialize()
		{
			$data               = (object) [];
			$data->intro        = $this->intro;
			$data->body         = $this->body;
			return $data;
		}

}