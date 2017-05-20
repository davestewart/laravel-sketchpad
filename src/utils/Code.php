<?php namespace davestewart\sketchpad\utils;

/**
 * Utility class for outputting code
 */
class Code
{

	/**
	 * Output text formatted as code
	 *
	 * @param   string  $text
	 * @param   string  $format
	 * @return  string
	 */
	public static function output($text, $format = 'php')
	{
		$text = htmlentities($text);
		return "<pre class='code $format'>$text</pre>\n";
	}

	/**
	 * Output the contents of an entire file
	 *
	 * @param   string  $path
	 * @param   string  $format
	 */
	public static function file($path, $format = '')
	{
		if ($format === '')
		{
			$format = self::getExtension($path);
		}
		$text = file_get_contents($path);
		self::output($text, $format);
	}

	/**
	 * Output a section of a single file
	 *
	 * @param   string  $path
	 * @param   int     $start
	 * @param   int     $end
	 * @param   bool    $undent
	 */
	public static function section($path, $start = 0, $end = 0, $undent = false)
	{
		$format = self::getExtension($path);
		$text   = file_get_contents($path);
		if ($start !== 0 || $end !== 0)
		{
			$text = self::lines($text, $start, $end);
			if ($undent)
			{
				$text = self::undent($text);
			}
		}
		self::output($text, $format);
	}

	/**
	 * Output the contents of a method
	 *
	 * @param   string  $class
	 * @param   string  $method
	 * @param   bool    $comment
	 */
	public static function method($class, $method, $comment = false)
	{
		$ref    = new \ReflectionMethod($class, $method);
		$start  = $ref->getStartLine();
		$end    = $ref->getEndLine();
		$text   = file_get_contents($ref->getFileName());
		$text   = self::lines($text, $start, $end);
		$text   = self::undent($text);
		if ($comment)
		{
			$text = preg_replace('/^\s+\*/m', ' *', $ref->getDocComment()) . PHP_EOL . $text;
		}
		self::output($text, 'php');
	}

	/**
	 * Output the contents of a class
	 *
	 * @param   string  $class
	 * @param   bool    $comment
	 */
	public static function classfile($class, $comment = false)
	{
		$ref    = new \ReflectionClass($class);
		$start  = $ref->getStartLine();
		$end    = $ref->getEndLine();
		$text   = file_get_contents($ref->getFileName());
		$text   = self::lines($text, $start, $end);
		if ($comment)
		{
			$text = preg_replace('/^\s+\*/m', ' *', $ref->getDocComment()) . PHP_EOL . $text;
		}
		self::output($text, 'php');
	}

	/**
	 * Return a range of lines from a block of text
	 *
	 * @param   string  $text
	 * @param   int     $start
	 * @param   int     $end
	 * @return  string
	 */
	public static function lines($text, $start, $end)
	{
		$lines      = explode(PHP_EOL, $text);
		$code       = implode(PHP_EOL, array_slice($lines, $start - 1, $end - $start + 1));
		return $code;
	}

	/**
	 * Return a formatted function signature
	 *
	 * @param   array   $args
	 * @return  string
	 */
	public static function signature($args = [])
	{
		$values = array_map(function ($v) {
			return is_string($v)
				? "'$v'"
				: (is_bool($v)
					? !! $v ? 'true' : 'false'
					: $v);
		}, $args);
		return '(' . implode(', ', $values) . ')';
	}

	/**
	 * Remove any indent from a block of text, based on the first line
	 *
	 * @param   string  $text
	 * @return  string
	 */
	public static function undent($text)
	{
		preg_match('/^\s+/', $text, $matches);
		list ($indent) = $matches;
		if ($indent)
		{
			$text = preg_replace("/^$indent/m", '', $text);
		}
		return $text;
	}

	/**
	 * Gets the file extension of a path
	 *
	 * @param   string  $path
	 * @return  string
	 */
	protected static function getExtension ($path)
	{
		return strpos($path, '/') !== NULL
			? substr($path, strrpos($path, '.') + 1)
			: '';
	}
}