<?php namespace davestewart\sketchpad\utils;

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
		 */
		public static function alert($html, $class = 'info')
		{
			if(is_bool($class))
			{
				$state  = !! $class;
				$class  = $state ? 'success' : 'danger';
				$icon   = $state ? 'check' : 'times';
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
			echo view('sketchpad::utils.list', $data);
		}

		/**
		 * List an array of objects in a nicely formatted table
		 *
		 * @param      $values
		 * @param string $params
		 */
		public static function tb($values, $params = '')
		{
			if(empty($values))
			{
				//throw  new \InvalidArgumentException('Parameter "$values" is an empty array');
				$values = [['' => '']];
			};

			$params = urldecode($params);
			//pr($params);
			$opts   = new Options($params);
			$keys   = array_keys( (array) $values[0]);
			$options =
			[
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

			//pr($options);
			$data = [
				'values'    => $values instanceof Arrayable
					? $values->toArray()
					: (array) $values,
			];
			echo view('sketchpad::utils.table', array_merge($data, $options));
		}


	// ------------------------------------------------------------------------------------------------
	// file format functions

		/**
		 * Loads and runs a Vue file in the UI
		 *
		 * @param            $path
		 * @param array|null $data
		 * @return mixed|string
		 */
		public static function vue($path, array $data = null)
		{
			$path   = \View::getFinder()->find($path);
			$str    = file_get_contents($path);
			if($data)
			{
				foreach($data as $key => $value)
				{
					$value = json_encode($value);
					$str = str_replace("%$key%", $value, $str);
				}
			}
			return $str;
		}

		/**
		 * Loads a Markdown file, or Markdown string into the UI and transforms it
		 *
		 * @param $path
		 * @return string
		 */
		public static function md($path)
		{
			$abspath = preg_match('%^(/|[a-z]:)%i', $path) === 1
				? $path
				: \View::getFinder()->find($path);
			header('Content-Type: text/markdown');
			return file_get_contents($abspath);
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

}

\View::addExtension('vue', '\davestewart\sketchpad\utils::vue');
\View::addExtension('md', '\davestewart\sketchpad\utils::md');
