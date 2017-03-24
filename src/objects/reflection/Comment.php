<?php namespace davestewart\sketchpad\objects\reflection;

use davestewart\sketchpad\traits\GetterTrait;
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
					if($name == 'param')
					{
						$this->params[$tag->name] = $tag;
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
					$this->tags['favourite'] = '';
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

		function jsonSerialize()
		{
			$data               = (object) [];
			$data->intro        = $this->intro;
			$data->body         = $this->body;
			return $data;
		}

}