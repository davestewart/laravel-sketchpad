<?php namespace davestewart\sketchpad\utils;

use Illuminate\Contracts\Support\Arrayable;

\View::addExtension('vue', 'vue');
\View::addExtension('md', 'md');

/**
 * Html class
 */
class Html
{

	// ------------------------------------------------------------------------------------------------
	// output functions

		/**
		 * Echo a paragraph tag, with optional class
		 *
		 * @param               $value
		 * @param bool|string   $class
		 */
		public static function p($value, $class = null)
		{
			$attr = $class === true
				? ' class="note"'
				: (is_string($class)
					? ' class="' .$class. '"'
					: '');
			echo "<p{$attr}>$value</p>";
		}

		/**
		 * Output a Bootstrap info / alert div
		 *
		 * @param   string  $html   The HTML or text to display
		 * @param   string  $class  An optional CSS class, can be info, success, warning, danger
		 * @param   string  $icon   An optional FontAwesome icon string
		 */
		public static function alert($html, $class = 'info', $icon = '')
		{
			if(is_bool($class))
			{
				$state  = !! $class;
				$class  = $state ? 'success' : 'danger';
				$icon   = $state ? 'check' : 'times';
			}
			if ($icon)
			{
				$html   = '<i class="fa fa-' .$icon. '" aria-hidden="true"></i> ' . $html;
			}
			echo '<div class="alert alert-' .$class. '" role="alert">' .$html. '</div>';
		}

		/**
		 * print_r() passed arguments
		 */
		public static function pr()
		{
			echo "\n" . '<pre style="font-size:11px">' . "\n";
			$args = func_get_args();
			print_r( count($args) === 1 ? $args[0] : $args);
			echo "</pre>\n\n";
		}

		/**
		 * print_r() and die
		 */
		public static function pd()
		{
			self::pr(func_get_args());
			exit;
		}

		/**
		 * var_dump() passed arguments
		 *
		 * @param $value
		 */
		public static function vd($value)
		{
			var_dump($value);
		}

		/**
		 * List an object's properties in a nicely formatted table
		 *
		 * @param        $values
		 * @param string $options
		 */
		public static function ls($values, $options = '')
		{
			$opts = new Options($options);
			$data =
			[
				'values'    => $values,
				'style'     => $opts->get('style', ''),
				'class'     => $opts->get('class', ''),
			];
			if($opts->pre === 1)
			{
				$data['class'] .= ' pre';
			}
			if($opts->wide)
			{
				$data['style'] .= ';width:100%;';
			}
			echo view('sketchpad::html.list', $data);
		}

		/**
		 * List an array of objects in a nicely formatted table
		 *
		 * @param      $values
		 * @param string $params
		 */
		public static function tb($values, $params = '')
		{
			$values = $values instanceof Arrayable
				? $values->toArray()
				: (array) $values;
			if(empty($values))
			{
				alert('Warning: tb() $values is empty', false);
				return;
			};

			$params = urldecode($params);
			//pr($params);
			$opts   = new Options($params);
			$keys   = array_keys( (array) $values[0]);
			$options =
			[
				'values'    => array_values($values),
				'keys'      => $keys,
				'label'     => $opts->get('label'),
				'index'     => $opts->has('index'),
				'class'     => $opts->get('class', ''),
				'style'     => $opts->get('style', ''),
				'width'     => $opts->get('width', ''),
				'cols'      => (array) $opts->get('cols'),
				'pre'       => (array) $opts->get('pre'),
				'html'      => (array) $opts->get('html'),
			];
			if($opts->pre === 1)
			{
				$options['class'] .= ' pre';
				$options['pre'] = [];
			}
			if($opts->width)
			{
				$options['style'] .= ';' . self::getCss($opts->width);
			}
			$options['cols'] = array_pad(array_map(function($value)
			{
				return self::getCss($value);
			}, $options['cols']), count($keys), '');

			echo view('sketchpad::html.table', $options);
		}


	// ------------------------------------------------------------------------------------------------
	// file format functions

		/**
		 * Converts to, and instructs Sketchpad to format an object as JSON in the front end
		 *
		 * Note that you can also have objects formatted as JSON by just returning them
		 *
		 * @param   mixed   $data
		 * @return  string
		 */
		public static function json($data)
		{
			echo '<div data-format="json">' .json_encode($data). '</div>';
		}

		/**
		 * Loads a Markdown file, and instructs Sketchpad to transform it in the front end
		 *
		 * @param   string  $path   An absolute or relative file reference
		 * @return  string
		 */
		public static function md($path)
		{
			$abspath = preg_match('%^(/|[a-z]:)%i', $path) === 1
				? $path
				: \View::getFinder()->find($path);
			echo '<div data-format="markdown">' .file_get_contents($abspath). '</div>';
		}

		/**
		 * Loads a Vue file and optionally injects data into it
		 *
		 * @param   string  $path
		 * @param   mixed   $data
		 * @return  string
		 */
		public static function vue($path, array $data = null)
		{
			$path   = \View::getFinder()->find($path);
			$str    = file_get_contents($path);
			if($data)
			{
				$tag1 = '<scr'.'ipt>';
				$tag2 = '</scr'.'ipt>';
				$json = json_encode($data);
				$str = str_replace($tag1, $tag1 . "(function () {\n\tvar \$data = $json;", $str);
				$str = str_replace($tag2, '}())' . $tag2, $str);
			}
			echo $str;
		}


	// ------------------------------------------------------------------------------------------------
	// utilities

		protected static function getCss($value, $css = 'width')
		{
			if(preg_match('/^[\d\.]+$/', $value))
			{
				$value .= 'px';
			}
			return "$css:$value";
		}

		public static function getText($value)
		{
			return is_bool($value)
				? $value ? 'true' : 'false'
				: $value;
		}

}
