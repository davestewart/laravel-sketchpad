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
		 * All blocks of text separated by 2 lines
		 *
		 * @var string[]
		 */
		public $paragraphs;

		/**
		 * Any initial paragraph
		 *
		 * @var string
		 */
		public $intro;

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
			// grab the entire comment body, trimming any faff
			$body           = trim(preg_replace('%^[\t ]*(/\*\*|\*/|\*[\t ]*)%m', "", $body));

			// strip windows line ends
			$body           = str_replace("\r\n", "\n", $body);

			// get text
			preg_match('/^([\s\S]+?)@\w+/', $body, $matches);
			if($matches)
			{
				$text = trim($matches[1]);
			}
			else
			{
				$text = trim(preg_replace('/^@.+/m', '', $body));
			}

			// split text into "paragraphs" at double-returns
			$this->paragraphs	= preg_split('%(\n){2}%', $text);

			// grab the first line
			$this->intro        = preg_replace('%[\r\n]+%', ' ', $this->paragraphs[0]);

			// tags
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
					$this->tags[$tag->name] = $tag->text;
				}
			}
			
			// fix favourites
			if(isset($this->tags['favorite']))
			{
				unset($this->tags['favorite']);
				$this->tags['favourite'] = '';
			}
		}

		function jsonSerialize()
		{
			$data               = (object) [];
			$data->intro        = $this->intro;
			$data->paragraphs   = $this->paragraphs;
			$data->tags         = $this->tags;
			return $data;
		}

}