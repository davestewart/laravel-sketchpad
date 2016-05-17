<?php

if( ! function_exists('tb') )
{
	function tb($values, $pre = false)
	{
		$data =
		[
			'values'    => $values instanceof Arrayable
							? $values->toArray()
							: (array) $values,
			'keys'      => array_keys( (array) $values[0]),
			'class'     => $pre ? 'pre' : 'table-striped',
		];
		echo view('sketchpad::utils.table', $data);
	}
}

if( ! function_exists('ls') )
{
	function ls($values, $pre = false)
	{
		$data =
		[
			'values' => $values,
			'class' => $pre ? 'pre' : 'table-striped',
		];
		echo view('sketchpad::utils.list', $data);
	}
}

if( ! function_exists('pr') )
{
	function pr()
	{
		echo '<pre style="font-size:11px">';
		$args = func_get_args();
		print_r( count($args) === 1 ? $args[0] : $args);
		echo '</pre>';
	}
}

if( ! function_exists('pd') )
{
	function pd()
	{
		call_user_func_array('pr', func_get_args());
		exit;
	}
}

if( ! function_exists('vd') )
{
	function vd($value)
	{
		var_dump($value);
	}
}

if( ! function_exists('p') )
{
	function p($value, $bold = false)
	{
		$class = $bold ? ' class="note"' : '';
		echo "<p$class>$value</p>";
	}
}

if( ! function_exists('alert') )
{
	/**
	 * Bootstrap info / alert box function
	 *
	 * @param   string  $html   The HTML or text to display
	 * @param   string  $class  An optional CSS class, can be info, success, warning, danger
	 */
	function alert($html, $class = 'info')
	{
		if(is_bool($class))
		{
			$state  = !! $class;
			$result = $state ? 'PASS' : 'FAIL';
			$class  = $state ? 'success' : 'danger';
			$icon   = $state ? 'check' : 'times';
			$html   = '<i class="fa fa-' .$icon. '" aria-hidden="true"></i> ' . $html;
		}
		echo '<div class="alert alert-' .$class. '" role="alert">' .$html. '</div>';
	}
}

if( ! function_exists('vue') )
{
	\View::addExtension('vue', 'vue');
	function vue($path, array $data = null)
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
}

if( ! function_exists('md') )
{
	\View::addExtension('md', 'md');
	function md($path)
	{
		$path = \View::getFinder()->find($path);
		header('Content-Type: text/markdown');
		return file_get_contents($path);
	}
}

